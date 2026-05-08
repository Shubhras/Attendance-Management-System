import SQLite from "react-native-sqlite-storage";
import { fetchEmployees } from "./src/api/auth";

export const db = SQLite.openDatabase(
  { name: "attendance.db", location: "default" },
  () => console.log("DB Opened"),
  error => console.log("DB Error", error)
);


// export const createTable = () => {
//     db.transaction(tx => {
//       tx.executeSql(`
//         CREATE TABLE IF NOT EXISTS employees (
//           id INTEGER PRIMARY KEY,
//           empId TEXT,
//           name TEXT,
//           fingerprintdata TEXT,
//           machinename TEXT
//         );
//       `);
//     });
//   };

export const createTable = () => {
    db.transaction(tx => {
      tx.executeSql(`
        CREATE TABLE IF NOT EXISTS employees (
          id INTEGER PRIMARY KEY,
          empId TEXT,
          name TEXT,
          profileimg TEXT,
          fingerprintdata TEXT,
          machinename TEXT
        );
      `);
    });
  };

//   export const insertEmployees = (employees) => {
//     db.transaction(tx => {
//       tx.executeSql("DELETE FROM employees");
  
//       employees.forEach(emp => {
//         tx.executeSql(
//           `INSERT INTO employees 
//           (id, empId, name, fingerprintdata, machinename) 
//           VALUES (?, ?, ?, ?, ?)`,
//           [
//             emp.id,
//             emp.empId,
//             emp.name,
//             emp.fingerprintdata,
//             emp.machinename
//           ]
//         );
//       });
//     });
//   };

const cleanBase64 = (str) => {
    if (!str) return "";
    return str.replace(/\s+/g, "");
  };
  
  export const insertEmployees = (employees) => {
    return new Promise((resolve, reject) => {
      db.transaction(
        tx => {
          tx.executeSql("DELETE FROM employees");
  
          employees.forEach(emp => {
            tx.executeSql(
              `INSERT INTO employees 
              (id, empId, name, profileimg, fingerprintdata, machinename) 
              VALUES (?, ?, ?, ?, ?, ?)`,
              [
                emp.id,
                emp.empId,
                emp.name,
                emp?.profileimg || "",
                cleanBase64(emp.fingerprintdata), // 🔥 FIX
                emp.machinename || "MFS500"
              ]
            );
          });
        },
        error => {
          console.log("Insert Error:", error);
          reject(error);
        },
        () => {
          console.log("✅ INSERT COMPLETED");
          resolve(true);
        }
      );
    });
  };

  export const resetTable = () => {
    db.transaction(tx => {
      tx.executeSql("DROP TABLE IF EXISTS employees");
    });
  };

  export const getEmployees = (machineName) => {
    return new Promise(resolve => {
        // db.transaction(tx => {
        //     tx.executeSql(
        //       "SELECT COUNT(*) as count FROM employees",
        //       [],
        //       (_, res) => {
        //         console.log("Total Employees:", res.rows.item(0).count);
        //       }
        //     );
        //   });
      db.transaction(tx => {
        tx.executeSql(
          "SELECT * FROM employees WHERE machinename = ?",
          [machineName],
          (_, results) => {
            let data = [];
  
            for (let i = 0; i < results.rows.length; i++) {
              data.push(results.rows.item(i));
            }

            resolve(data);
            
          }
        );
      });
    });
  };

//   export const syncEmployees = async () => {
//     const data = await fetchEmployees();
  
//     if (data.length > 0) {
//       insertEmployees(data);
//       console.log("Sync done:", data.length);
//     }
//   };

// export const syncEmployees = async (access_token) => {
//     try {
//       console.log("Sync started...");
  
//       const response = await fetchEmployees(access_token);
//   console.log("All EMP Lists",response);
  
//       const employees = Array.isArray(response)
//         ? response
//         : response?.data || [];
  
//       console.log("FINAL EMP LIST:", employees.length);
  
//       if (!employees.length) return;
  
//       // ✅ IMPORTANT
//       await insertEmployees(employees);
  
//       console.log("✅ Sync done:", employees.length);
  
//     } catch (error) {
//       console.log("Sync error:", error);
//     }
//   };

export const syncEmployees = async (access_token) => {
  try {
    console.log("Sync started...");

    const response = await fetchEmployees(access_token);

    console.log("All EMP Lists", response);

    const employees = Array.isArray(response)
      ? response
      : response?.data || [];

    console.log("FINAL EMP LIST:", employees.length);

    if (!employees.length) {
      return { success: false, message: "No employees found" };
    }

    await insertEmployees(employees);

    console.log("✅ Sync done:", employees.length);

    return { success: true };

  } catch (error) {
    console.log("Sync error:", error);
    return { success: false, message: "Something went wrong" };
  }
};

