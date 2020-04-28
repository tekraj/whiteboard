import { Request } from "express";

const attachRequestToResponse = (req: Request): boolean => {
  const RETURN_REQ = Number(process.env.RETURN_REQ);
  const ALWAYS_RETURN_REQ = Number(process.env.ALWAYS_RETURN_REQ);
  if (!RETURN_REQ) return false;
  if (ALWAYS_RETURN_REQ) return true;
  if (req.body.returnRequest) return true;
  return false;
};
export default attachRequestToResponse;
