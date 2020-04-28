import { Request } from "express";
const attachRequestToResponse = (req: Request) => {
  let RETURN_REQ: number = Number(process.env.RETURN_REQ);
  let ALWAYS_RETURN_REQ: Number = Number(process.env.ALWAYS_RETURN_REQ);
  if (!RETURN_REQ) return false;
  if (ALWAYS_RETURN_REQ) return true;
  if (req.body.returnRequest) return true;
  return false;
};
export { attachRequestToResponse };
