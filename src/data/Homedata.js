import Employee from '../assets/images/Animation/Employee.gif';
import Attendance from '../assets/images/Animation/Attendance.gif';
import Machine from '../assets/images/Animation/Machine.gif';
import Report from '../assets/images/Animation/Report.gif';
import Contractors from '../assets/images/Animation/Contractors.gif';
import Fingerprint from '../assets/images/Animation/Fingerprint.gif';

export const getHomeData = countData => {
  return [
    {
      id: 1,
      title: 'My Employee',
      subtitle: 'Total Employees',
      icon: require('../assets/images/Animation/Employee.gif'),
      defaultSource: Employee,
      onPress: 'MyEmployee',
      subtitleValue: countData?.employees_count || 0,
    },
    {
      id: 2,
      title: 'Add Attendance',
      subtitle: 'Today',
      subtitleValue: '',
      icon: require('../assets/images/Animation/Attendance.gif'),
      defaultSource: Attendance,
      onPress: 'MarkAttendance',
    },
    {
      id: 3,
      title: 'My Machine',
      subtitle: 'Total Machines',
      subtitleValue: countData?.machines_count || 0,
      icon: require('../assets/images/Animation/Machine.gif'),
      defaultSource: Machine,
      onPress: 'MyMachine',
    },
    {
      id: 4,
      title: 'Full Employees Report',
      subtitle: 'Date',
      subtitleValue: countData?.current_date || '',
      icon: require('../assets/images/Animation/Report.gif'),
      defaultSource: Report,
      onPress: 'MyExport',
    },
    {
      id: 5,
      title: 'Report By Contractors',
      subtitle: 'Total Contractors',
      subtitleValue: countData?.contractor_count || 0,
      icon: require('../assets/images/Animation/Contractors.gif'),
      defaultSource: Contractors,
      onPress: 'ContractorListScreen',
    },
    {
      id: 6,
      title: 'Add Fingerprint',
      subtitle: 'Total Employees',
      subtitleValue: countData?.fingerprint_false_count || 0,
      icon: require('../assets/images/Animation/Fingerprint.gif'),
      defaultSource: Fingerprint,
      onPress: 'FingerPrintEmployeeList',
    },
  ];
};

export default getHomeData;
