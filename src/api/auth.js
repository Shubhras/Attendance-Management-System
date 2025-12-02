import axios from "axios";
import { API_URL } from '../../env'



export const LoginAPI = async (data) => {
    return new Promise((resolve, reject) => {
        const config = {
            method: 'POST',
            url: `${API_URL}/api/operator/login`,
            headers: {
                'Content-Type': 'multipart/form-data',
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


export const getEmployeList = async (token, searchText, pageNumber) => {
    return new Promise((resolve, reject) => {
        const config = {
            method: 'GET',
            url: `${API_URL}/api/employees?search=${searchText}&page=${pageNumber}`,
            headers: {
                'Content-Type': 'Application/json',
                Authorization: `Bearer ${token}`
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
export const getByMachineEmployeList = async (token, machineId, searchText, pageNumber) => {
    return new Promise((resolve, reject) => {
        const config = {
            method: 'GET',
            url: `${API_URL}/api/get-machines/${machineId}/employees?search=${searchText}&page=${pageNumber}`,
             headers: {
                'Content-Type': 'Application/json',
                Authorization: `Bearer ${token}`
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


export const getEmployeeInfo = async (token, id) => {
    return new Promise((resolve, reject) => {
        const config = {
            method: 'GET',
            url: `${API_URL}/api/employees/${id}`,
            headers: {
                'Content-Type': 'Application/json',
                Authorization: `Bearer ${token}`
            },
        };
        axios
            .request(config)
            .then(response => {
                resolve(response.data);
                console.log('response.data', response.data)
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
                Authorization: `Bearer ${token}`
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


export const fingerPrintAdd = async (data) => {
    return new Promise((resolve, reject) => {
        const config = {
            method: 'POST',
            url: `${API_URL}/api/thumb-machine/store`,

            headers: {
                'Content-Type': 'Application/json',
                // Authorization: `Bearer ${token}`
            },
            data:data
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


