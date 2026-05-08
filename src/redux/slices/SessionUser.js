import { createSlice } from '@reduxjs/toolkit';

const initialState = {
  users: {},
  welcomeFlag:false,
  employeeList: [],   // ✅ must
  employeeMap: {}     // ✅ must  // 👈 ADD THIS
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
    addEmployee: (state, action) => {
      const emp = action.payload;
    
      // safety check 🔥
      if (!state.employeeList) {
        state.employeeList = [];
      }
    
      if (!state.employeeMap) {
        state.employeeMap = {};
      }
    
      state.employeeList.push(emp);
      state.employeeMap[emp.empId] = emp;
    }
  },
});

export const { loginUser, updateUser, logoutUser,welcomeUser, addEmployee } = usersSlice.actions;
export default usersSlice.reducer;
