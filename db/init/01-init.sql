-- CREATE USER setoboard;
-- CREATE DATABASE setoboard;
-- GRANT ALL PRIVILEGES ON DATABASE setoboard TO setoboard;
CREATE TABLE data (
  ID SERIAL PRIMARY KEY,
  firstname VARCHAR(255),
  lastname VARCHAR(255)
);
INSERT INTO data (firstname, lastname)
VALUES
  ('Atul', 'Shrestha');
