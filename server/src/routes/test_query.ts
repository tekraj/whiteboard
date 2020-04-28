// FIXME: Convert to typescript!

import express, { Request, Response } from "express";
const router = express.Router()
const Pool = require('pg').Pool
const { POSTGRES_HOST, POSTGRES_PORT, POSTGRES_USER, POSTGRES_PASSWORD, POSTGRES_DB } = process.env

const pool = new Pool({
  host: POSTGRES_HOST,
  port: POSTGRES_PORT,
  user: POSTGRES_USER,
  password: POSTGRES_PASSWORD,
  database: POSTGRES_DB
})

//
interface IUserData {
  rows: {
    firstname: string;
    lastname: string;
  }[]

};
router.get('/', (req: Request, res: Response) => {
  console.log('Test query!');

  let q = 'SELECT * FROM data ORDER BY id ASC';
  try {
    pool.query(q, (error: any, results: IUserData) => {
      if (error) {
        // throw error
        return res.status(500).json(error)
      }
      res.status(200).json(results.rows)
    })

  } catch (err) {
    res.status(200).json(err)
  }
})


export default router
