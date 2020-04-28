import express, { Request as IRequest, Response as IResponse } from "express";
import { Pool } from "pg";

const router = express.Router();

const {
  POSTGRES_HOST,
  POSTGRES_PORT,
  POSTGRES_USER,
  POSTGRES_PASSWORD,
  POSTGRES_DB,
} = process.env;

const pool = new Pool({
  host: POSTGRES_HOST,
  port: Number(POSTGRES_PORT),
  user: POSTGRES_USER,
  password: POSTGRES_PASSWORD,
  database: POSTGRES_DB,
});

//
interface IUserData {
  rows: {
    firstname: string;
    lastname: string;
  }[];
}
/* eslint consistent-return: "off" */

router.get(`/`, (req: IRequest, res: IResponse) => {
  console.info(`Test query!`);

  const q = `SELECT * FROM data ORDER BY id ASC`;
  try {
    pool.query(q, (error: any, results: IUserData) => {
      if (error) {
        // throw error
        return res.status(500).json(error);
      }
      return res.status(200).json(results.rows);
    });
  } catch (err) {
    return res.status(200).json(err);
  }
});

export default router;
