import axios from 'axios';
import { API_URL } from '../../env';

export const LoginAPI = async data => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'POST',
      url: `${API_URL}/api/operator/login`,
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      data: data,
    };
    console.log('config', config);
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
        console.log('responselogin', response);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};

export const getEmployeList = async (
  token,
  searchText,
  pageNumber,
  fingerprint_status = false,
) => {
  let urlstring =
    fingerprint_status == true
      ? `${API_URL}/api/employees?search=${searchText}&page=${pageNumber}&fingerprint_status=false`
      : `${API_URL}/api/employees?search=${searchText}&page=${pageNumber}`;
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: urlstring,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    console.log('config', config);
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};

export const GetEmployeesWithoutFingerprint = async (
  token,
  searchText,
  pageNumber,
) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: `${API_URL}/api/no-fingerprint/get?search=${searchText}&page=${pageNumber}`,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    console.log('config no-fingerprint', config);

    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};

export const getByMachineEmployeList = async (
  token,
  machineId,
  searchText,
  pageNumber,
) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: `${API_URL}/api/get-machines/${machineId}/employees?search=${searchText}&page=${pageNumber}`,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    console.log('config', config);

    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};

export const getEmployeeInfo = async (token, id) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: `${API_URL}/api/employees/${id}`,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
        console.log('response.data', response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};

export const getMachines = async (token, searchText, pageNumber) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: `${API_URL}/api/get-machines?search=${searchText}&page=${pageNumber}`,

      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};

export const getContractorsApi = async (token, searchText, pageNumber) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: `${API_URL}/api/get-contractors-employee?search=${searchText}&page=${pageNumber}`,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};

export const AddFingerPrint = async (token, data) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'POST',
      url: `${API_URL}/api/operator/fingerprint/store`,

      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
      data: data,
    };
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};

export const AttendanceMark = async (token, data) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'POST',
      url: `${API_URL}/api/operator/attendance/mark`,

      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
      data: data,
    };
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};


export const HomeCount = async token => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: `${API_URL}/api/total-counts`,

      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};


export const AllEmpReports = async (token, startYMD, endYMD) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: `${API_URL}/api/operator/attendance/report/pdf?start_date=${startYMD}&end_date=${endYMD}`,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};
// https://attendanceapp.digiprima.co/api/operator/employees/15/attendance/monthly?year=2025&date=2025-12-01&month=12

export const MonthlyEmpReport = async ({token, id, year, date='', month=''}) => {
  return new Promise((resolve, reject) => {
    const config = {
      method: 'GET',
      url: `${API_URL}/api/operator/employees/${id}/attendance/monthly?year${year}&date=${date}&month=${month}`,
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `Bearer ${token}`,
      },
    };
    axios
      .request(config)
      .then(response => {
        resolve(response.data);
      })
      .catch(error => {
        if (error.response) {
          reject(error.response.data);
        } else if (error.request) {
          reject(error);
        } else {
          reject(error);
        }
      });
  });
};
