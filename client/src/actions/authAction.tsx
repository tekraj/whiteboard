import { SET_CURRENT_USER } from "./types";
import { GET_ERROR } from "./types";
import jwt from "jwt-simple";

export const loginuser = (user: any) => (dispatch: any) => {
  console.log("User information", user);
  const token = jwt.encode(user, "secret");
  localStorage.setItem("jwtToken", token);
  var decoded = jwt.decode(token, "secret");
  console.log("loginuser -> decoded", decoded);
  dispatch(setCurrentUser(decoded));
};

export const setCurrentUser = (decoded: any) => {
  return {
    type: SET_CURRENT_USER,
    payload: decoded,
  };
};
