import express, { Application, Request, Response, NextFunction } from "express";

const app: Application = express();

app.get(``, (_: Request, res: Response, __: NextFunction) => {
  res.send(`🚀`);
});

app.listen(5000, () => console.log(`server running!!`));
