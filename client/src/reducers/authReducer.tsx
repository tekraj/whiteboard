import { SET_CURRENT_USER } from "../actions/types";
const initialState = {
  isAuthenticated: false,
  user: {},
};

export default function (state = initialState, action: any) {
  switch (action.type) {
    case SET_CURRENT_USER:
      return {
        ...state,
        isAuthenticated: true,
        user: action.payload,
      };
    default:
      return state;
  }
}
