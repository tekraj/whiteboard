// Convert to typescript!
import { Router, Request, Response } from "express";

import testQueryRouter from "./test_query";

const router = Router();
router.get(`/`, (_req: Request, res: Response) => {
  res.json({
    message: `🚀`,
  });
});

router.use(`/test_query`, testQueryRouter);

module.exports = router;
