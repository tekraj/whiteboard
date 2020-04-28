import { HttpError } from "http-errors";

import rateLimit from "express-rate-limit";
import { Request, Response, NextFunction } from "express";

import mung from "express-mung";
import cloneDeep from "lodash.clonedeep";
import attachRequestToResponse from "../helpers";

// FIXME: Move interfaces to a different location

// interface Error {
//   message?: string;
//   stack: any;
// }

// Not found
const notFound = (req: Request, res: Response, next: NextFunction) => {
  const error = new Error(`Not Found - ${req.method} ${req.originalUrl}`);
  res.status(404);
  next(error);
};

// Error handling
const errorHandler = (
  error: HttpError,
  _req: Request,
  res: Response,
  next: NextFunction
) => {
  const statusCode = res.statusCode === 200 ? 500 : res.statusCode;
  res.status(statusCode);
  res.json({
    success: false,
    message: error.message,
    error: process.env.NODE_ENV === `production` ? `🥞` : error.stack,
  });
  next();
};

const cloneRequestObject = (
  req: Request,
  res: Response,
  next: NextFunction
) => {
  if (attachRequestToResponse(req)) {
    res.locals.requestClone = {
      body: cloneDeep(req.body),
      headers: cloneDeep(req.headers),
    };
  }
  next();
};
// eslint-disable-next-line no-unused-vars
const modifyResponseBody = (body: any, req: Request, res: Response) => {
  // Modify response here
  // Attach the request to response, if requested
  if (attachRequestToResponse(req && res.locals && res.locals.requestClone)) {
    body.request = res.locals.requestClone;
  }
  return body;
};

// TODO: Switch to a redis store in production!
const rateLimiter = (
  windowMinutes: number = 0,
  maxReq: number = 0,
  resHeaders = true
) => {
  // maxReq in windowMinutes will block the user!
  const windowMs =
    (windowMinutes || Number(process.env.LIMIT_WINDOW) || 15) * 60 * 1000;
  const max = maxReq || Number(process.env.LIMIT_REQ) || 100;
  return rateLimit({
    windowMs,
    max,
    headers: resHeaders,
    statusCode: 400,
    handler(_req: Request, res: Response, next: NextFunction) {
      const error = new Error(`Too many requests!`);
      res.status(418);
      next(error);
    },
  });
};
export default {
  notFound,
  errorHandler,
  modifyResponseBody: mung.json(modifyResponseBody, { mungError: true }),
  cloneRequestObject,
  rateLimiter,
};
