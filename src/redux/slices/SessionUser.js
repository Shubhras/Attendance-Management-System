import { createSlice } from '@reduxjs/toolkit';

const initialState = {
  users: {},
  welcomeFlag:false
};

const usersSlice = createSlice({
  name: 'users',
  initialState,
  reducers: {
    loginUser: (state, action) => {
      state.users = action.payload;
    },
    updateUser: (state, action) => {
      state.users = { ...state.users, ...action.payload };
    },
    logoutUser: (state) => {
      state.users = {};
    },
    welcomeUser: (state, action) => {
      state.welcomeFlag = action.payload;  
    },
  },
});

export const { loginUser, updateUser, logoutUser,welcomeUser } = usersSlice.actions;
export default usersSlice.reducer;
