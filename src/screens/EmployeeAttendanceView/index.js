// import React, { useEffect, useState } from 'react';
// import { FlatList, Pressable, View } from 'react-native';
// import styles from './styles.js';
// import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
// import { Colors, LightThemeColors } from '../../config/Colors.js';
// import Header from '../../components/header/index.js';
// import DayDetailsCard from '../../components/cards/DayDetailsCard/index.js';
// import { scale } from 'react-native-size-matters';
// import { CustomText } from '../../components/global/CustomComponents.js';
// import Icons from '../../components/Icons/Icons.js';
// import MonthPicker from 'react-native-month-year-picker';
// import { MonthlyEmpReport } from '../../api/auth.js';
// import { useSelector } from 'react-redux';

// const yearData = [
//   {
//     month: "January 2025",
//     data: [
//       { day: "Wed", date: "2025-01-01", time_in: null, time_out: null, status: "Absent" },
//       { day: "Thu", date: "2025-01-02", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Fri", date: "2025-01-04", time_in: "08:07 AM", time_out: "17:02 PM", status: "Pending" },
//       { day: "Sat", date: "2025-01-05", time_in: "08:05 AM", time_out: "17:00 PM", status: "Absent" },
//       { day: "Mon", date: "2025-01-07", time_in: "08:07 AM", time_out: "17:02 PM", status: "Half day" },
//     ]
//   },
//   {
//     month: "February 2025",
//     data: [
//       { day: "Sat", date: "2025-02-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Sun", date: "2025-02-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "March 2025",
//     data: [
//       { day: "Sat", date: "2025-03-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Sun", date: "2025-03-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "April 2025",
//     data: [
//       { day: "Tue", date: "2025-04-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Wed", date: "2025-04-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "May 2025",
//     data: [
//       { day: "Thu", date: "2025-05-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Fri", date: "2025-05-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "June 2025",
//     data: [
//       { day: "Sun", date: "2025-06-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Mon", date: "2025-06-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "July 2025",
//     data: [
//       { day: "Tue", date: "2025-07-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Wed", date: "2025-07-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "August 2025",
//     data: [
//       { day: "Fri", date: "2025-08-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Sat", date: "2025-08-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "September 2025",
//     data: [
//       { day: "Mon", date: "2025-09-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Tue", date: "2025-09-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "October 2025",
//     data: [
//       { day: "Wed", date: "2025-10-01", time_in: "08:08 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Thu", date: "2025-10-02", time_in: null, time_out: null, status: "Absent" },
//       { day: "Fri", date: "2025-10-03", time_in: "08:02 AM", time_out: null, status: "Pending" },
//       { day: "Sat", date: "2025-10-04", time_in: "08:05 AM", time_out: "16:27 PM", status: "Present" },
//       { day: "Mon", date: "2025-10-06", time_in: "08:02 AM", time_out: null, status: "Half day" },
//     ]
//   },
//   {
//     month: "November 2025",
//     data: [
//       { day: "Sat", date: "2025-11-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Sun", date: "2025-11-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   },
//   {
//     month: "December 2025",
//     data: [
//       { day: "Mon", date: "2025-12-01", time_in: "08:00 AM", time_out: "17:00 PM", status: "Present" },
//       { day: "Tue", date: "2025-12-02", time_in: null, time_out: null, status: "Absent" },
//     ]
//   }
// ];

// const Filters = [
//   { key: 'all', level: 'All' },
//   { key: 'present', level: 'Present' },
//   { key: 'absent', level: 'Absent' },
//   { key: 'half_day', level: 'Half-day' },
// ];

// const EmployeeAttendanceView = ({route}) => {
//   const {id} = route.prams || {};
//   const [showPicker, setShowPicker] = useState(false);
//   const [selectedMonthIndex, setSelectedMonthIndex] = useState(0);
//   const [selectedFilter, setSelectedFilter] = useState('all');
//   const user = useSelector(state => state.users.users);
//   const token = user?.access_token;

//   const currentMonth = yearData[selectedMonthIndex];
//   const currentMonthData = currentMonth.data;

//   const filteredData = currentMonthData.filter((item) => {
//     if (selectedFilter === 'all') return true;
//     if (selectedFilter === 'half_day') return item.status?.toLowerCase() === 'half day';
//     return item.status?.toLowerCase() === selectedFilter;
//   });

//   const handlePrevMonth = () => {
//     if (selectedMonthIndex > 0) setSelectedMonthIndex(selectedMonthIndex - 1);
//   };

//   const handleNextMonth = () => {
//     if (selectedMonthIndex < yearData.length - 1) setSelectedMonthIndex(selectedMonthIndex + 1);
//   };

//   const showMonthPickerHandler = () => setShowPicker(true);

//   const onMonthChange = (event, newDate) => {
//     if (event === 'dateSetAction' && newDate) {
//       const monthIndex = newDate.getMonth(); // 0 = Jan, 1 = Feb
//       setSelectedMonthIndex(monthIndex);
//     }
//     setShowPicker(false);
//   };

//   const MonthReport = async () => {
//     MonthlyEmpReport({token, id, year, date, month})
//     .then((res) => {
//       console.log('res',res);

//     })
//     .catch((error) => {
//       console.log('error', error);

//     })
//   };

//   useEffect(() =>{
//     MonthReport()
//   }, []);

//   const renderItem = ({ item }) => (
//     <DayDetailsCard
//       status={item?.status}
//       time_in={item?.time_in}
//       date={item?.date}
//       day={item?.day}
//     />
//   );

//   return (
//     <CustomSafeAreaView
//       statusBarBackgroundColor={LightThemeColors.titleColor}
//       barStyle="white"
//     >
//       <View style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
//         <Header
//           back
//           title="John Doe"
//           headerBg={LightThemeColors.titleColor}
//           iconColor={Colors.white}
//           style={{ height: scale(50) }}
//         />

//         {/* Month Navigation */}
//         <View style={styles.monthNavigator}>
//           <Pressable onPress={handlePrevMonth} disabled={selectedMonthIndex === 0}>
//             <Icons
//               name="chevron-back-sharp"
//               iconType="Ionicons"
//               size={scale(22)}
//               color={selectedMonthIndex === 0 ? Colors.gray : Colors.black}
//             />
//           </Pressable>

//           <Pressable onPress={showMonthPickerHandler}>
//             <CustomText style={styles.monthText}>{currentMonth.month}</CustomText>
//           </Pressable>

//           <Pressable onPress={handleNextMonth} disabled={selectedMonthIndex === yearData.length - 1}>
//             <Icons
//               name="chevron-forward-sharp"
//               iconType="Ionicons"
//               size={scale(22)}
//               color={selectedMonthIndex === yearData.length - 1 ? Colors.gray : Colors.black}
//             />
//           </Pressable>
//         </View>

//         {/* Filters */}
//         <View style={styles.filter}>
//           {Filters.map((item) => {
//             const isActive = selectedFilter === item.key;
//             return (
//               <Pressable
//                 key={item.key}
//                 onPress={() => setSelectedFilter(item.key)}
//                 style={[
//                   styles.filterButton,
//                   {
//                     backgroundColor: isActive
//                       ? LightThemeColors.titleColor
//                       : Colors.white,
//                     borderColor: LightThemeColors.titleColor,
//                     borderWidth: scale(1),
//                   },
//                 ]}
//               >
//                 <CustomText
//                   style={[
//                     styles.filterText,
//                     { color: isActive ? Colors.white : Colors.black },
//                   ]}
//                 >
//                   {item.level}
//                 </CustomText>
//               </Pressable>
//             );
//           })}
//         </View>

//         {/* Attendance List */}
//         <FlatList
//           style={styles.flateList}
//           data={filteredData}
//           keyExtractor={(item, index) => index.toString()}
//           renderItem={renderItem}
//           showsVerticalScrollIndicator={false}
//           contentContainerStyle={styles.contentContainerStyle}
//         />

//         {showPicker && (
//           <MonthPicker
//             onChange={onMonthChange}
//             value={new Date()}
//             minimumDate={(() => {
//               const today = new Date();
//               const lastThree = new Date(today.getFullYear(), today.getMonth() - 2, 1);
//               return lastThree;
//             })()}
//             maximumDate={new Date()}
//             locale="en"
//             mode="short"
//           />
//         )}
//       </View>
//     </CustomSafeAreaView>
//   );
// };

// export default EmployeeAttendanceView;

import React, { useEffect, useState } from 'react';
import {
  ActivityIndicator,
  FlatList,
  Pressable,
  StatusBar,
  View,
} from 'react-native';
import moment from 'moment';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import DayDetailsCard from '../../components/cards/DayDetailsCard/index.js';
import { scale } from 'react-native-size-matters';
import { CustomText } from '../../components/global/CustomComponents.js';
import Icons from '../../components/Icons/Icons.js';
import MonthPicker from 'react-native-month-year-picker';
import { MonthlyEmpReport } from '../../api/auth.js';
import { useSelector } from 'react-redux';
import { showMessage } from 'react-native-flash-message';
import { SafeAreaView } from 'react-native-safe-area-context';
import EmptyCart from '../../components/alerts/EmptyCart/index.js';
import FastImage from '@d11/react-native-fast-image';
import Loading from '../../assets/images/Animation/Loading.gif';
import { Images } from '../../constants/images.js';

const Filters = [
  { key: 'all', level: 'All' },
  { key: 'present', level: 'Present' },
  { key: 'absent', level: 'Absent' },
  { key: 'half_day', level: 'Half-day' },
];

const EmployeeAttendanceView = ({ route }) => {
  const { id, month, year } = route.params || {};

  const [loading, setLoading] = useState(false);
  const [attendanceData, setAttendanceData] = useState([]);
  const [selectedFilter, setSelectedFilter] = useState('all');
  const [currentMoment, setCurrentMoment] = useState(null);
  const [showPicker, setShowPicker] = useState(false);
  const [summary, setSummary] = useState({ present: 0, leave: 0, half_day: 0 });

  const user = useSelector(state => state.users.users);
  const token = user?.access_token;

  // Initialize current month
  useEffect(() => {
    if (month && year) {
      const providedMoment = moment(`${year}-${month}`, 'YYYY-MM');
      setCurrentMoment(providedMoment);
    } else {
      setCurrentMoment(moment());
    }
  }, []);

  // Get status text based on API status code
  const getStatusText = (statusCode, clockIn, clockOut) => {
    if (statusCode === 1 && clockIn && clockOut) {
      return 'Present';
    } else if (statusCode === 1 && clockIn && !clockOut) {
      return 'Pending';
    } else if (statusCode === 0) {
      return 'Absent';
    } else if (statusCode === 2) {
      return 'Half day';
    }
    return 'Absent';
  };

  // Format time from 24hr to 12hr format
  const formatTime = timeString => {
    if (!timeString) return null;
    return moment(timeString, 'HH:mm:ss').format('hh:mm A');
  };

  // Fetch attendance data
  const getMonthReport = selectedMoment => {
    if (!id || !selectedMoment) {
      console.log('Missing required parameters');
      return;
    }

    const monthParam = selectedMoment.format('MM');
    const yearParam = selectedMoment.format('YYYY');

    setLoading(true);

    MonthlyEmpReport({
      token,
      id,
      month: monthParam,
      year: yearParam,
    })
      .then(response => {
        console.log('Monthly Report Response:', response);

        if (response?.status === true) {
          // Format attendance data from API response
          const formattedData = (response?.data || []).map(item => {
            const attendanceDate = moment(item.date);
            const status = getStatusText(
              item.status,
              item.clock_in,
              item.clock_out,
            );

            return {
              id: item.id,
              date: attendanceDate.format('YYYY-MM-DD'),
              day: attendanceDate.format('ddd'),
              time_in: formatTime(item.clock_in),
              time_out: formatTime(item.clock_out),
              status: status,
              raw_status: item.status,
              scan_status: item.scan_status,
            };
          });

          setAttendanceData(formattedData);

          // Set summary data
          if (response?.summary) {
            setSummary(response.summary);
          }
        } else {
          showMessage({
            message: 'Error',
            description:
              response?.message || 'Failed to fetch attendance data.',
            type: 'danger',
          });
          setAttendanceData([]);
          setSummary({ present: 0, leave: 0, half_day: 0 });
        }
        setLoading(false);
      })
      .catch(error => {
        console.error('Error fetching attendance:', error);
        showMessage({
          message: 'Error',
          description:
            error?.message || 'Something went wrong. Please try again.',
          type: 'danger',
        });
        setAttendanceData([]);
        setSummary({ present: 0, leave: 0, half_day: 0 });
        setLoading(false);
      });
  };

  // Fetch data when currentMoment changes
  useEffect(() => {
    if (currentMoment) {
      getMonthReport(currentMoment);
    }
  }, [currentMoment]);

  // Filter attendance data
  const filteredData = attendanceData.filter(item => {
    if (selectedFilter === 'all') return true;
    if (selectedFilter === 'half_day')
      return item?.status?.toLowerCase() === 'half day';
    return item?.status?.toLowerCase() === selectedFilter;
  });

  // Handle month navigation
  const handlePrevMonth = () => {
    if (!currentMoment) return;

    const newMoment = currentMoment.clone().subtract(1, 'months');
    const threeMonthsAgo = moment().subtract(2, 'months').startOf('month');

    if (newMoment.isSameOrAfter(threeMonthsAgo)) {
      setCurrentMoment(newMoment);
    }
  };

  const handleNextMonth = () => {
    if (!currentMoment) return;

    const newMoment = currentMoment.clone().add(1, 'months');
    const today = moment().startOf('month');

    if (newMoment.isSameOrBefore(today)) {
      setCurrentMoment(newMoment);
    }
  };

  // Check if navigation buttons should be disabled
  const isPrevDisabled = () => {
    if (!currentMoment) return true;
    const threeMonthsAgo = moment().subtract(2, 'months').startOf('month');
    return currentMoment.isSameOrBefore(threeMonthsAgo);
  };

  const isNextDisabled = () => {
    if (!currentMoment) return true;
    const today = moment().startOf('month');
    return currentMoment.isSameOrAfter(today);
  };

  // Get current month label
  const getCurrentMonthLabel = () => {
    return currentMoment ? currentMoment.format('MMMM YYYY') : '';
  };

  // MonthPicker handlers
  const showMonthPickerHandler = () => setShowPicker(true);

  const onMonthChange = (event, newDate) => {
    if (event === 'dateSetAction' && newDate) {
      setCurrentMoment(moment(newDate));
    }
    setShowPicker(false);
  };

  // Calculate min and max dates for last 3 months
  const getDateLimits = () => {
    const today = new Date();
    const currentYear = today.getFullYear();

    // Minimum date: 3 months ago from current month, day 1
    const minDate = new Date(currentYear, today.getMonth() - 2, 1);

    // Maximum date: Current month, last day
    const maxDate = new Date(currentYear, today.getMonth() + 1, 0);

    return { minDate, maxDate };
  };

  const { minDate, maxDate } = getDateLimits();

  const renderItem = ({ item }) => (
    <DayDetailsCard
      status={item?.status}
      time_in={item?.time_in}
      time_out={item?.time_out}
      date={item?.date}
      day={item?.day}
    />
  );

  const renderEmptyComponent = () => (
    <>
      {loading ? (
        <View style={styles.imageContainer}>
          <FastImage
            source={Images.Loading}
            defaultSource={Loading}
            style={styles.image}
            resizeMode="cover"
          />
        </View>
      ) : (
        <EmptyCart message="No attendance records found for this month." />
      )}
    </>
  );

  return (
    <SafeAreaView
      style={[styles.mainWrapper, { backgroundColor: Colors.primary }]}
    >
      <StatusBar
        animated={true} // Animate transitions between style changes
        backgroundColor="transparent" // Make status bar transparent (requires translucent=true)
        barStyle="light-content" // Set text and icon color (light-content or dark-content)
        hidden={false} // Show or hide the status bar
        translucent={true} // Allow content to draw under the status bar
      />
      <View style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
        <Header
          back
          title="Attendance View"
          headerBg={LightThemeColors.titleColor}
          iconColor={Colors.white}
          style={{ height: scale(50) }}
        />

        {/* Month Navigation */}
        <View style={styles.monthNavigator}>
          <Pressable onPress={handlePrevMonth} disabled={isPrevDisabled()}>
            <Icons
              name="chevron-back-sharp"
              iconType="Ionicons"
              size={scale(22)}
              color={isPrevDisabled() ? Colors.lightgary : Colors.black}
            />
          </Pressable>

          <Pressable onPress={showMonthPickerHandler}>
            <CustomText style={styles.monthText}>
              {getCurrentMonthLabel()}
            </CustomText>
          </Pressable>

          <Pressable onPress={handleNextMonth} disabled={isNextDisabled()}>
            <Icons
              name="chevron-forward-sharp"
              iconType="Ionicons"
              size={scale(22)}
              color={isNextDisabled() ? Colors.lightgary : Colors.black}
            />
          </Pressable>
        </View>

        {/* Filters */}
        <View style={styles.filter}>
          {Filters.map((item, i) => {
            const isActive = selectedFilter === item.key;
            return (
              <Pressable
                key={item.key}
                onPress={() => setSelectedFilter(item.key)}
                style={[
                  styles.filterButton,
                  {
                    backgroundColor: isActive
                      ? LightThemeColors.titleColor
                      : Colors.white,
                    borderColor: LightThemeColors.titleColor,
                    borderWidth: scale(1),
                  },
                ]}
              >
                <CustomText
                  style={[
                    styles.filterText,
                    { color: isActive ? Colors.white : Colors.black },
                  ]}
                >
                  {i == 1
                    ? item.level + ' (' + summary.present + ')'
                    : i == 2
                    ? item.level + ' (' + summary.leave + ')'
                    : i == 3
                    ? item.level + ' (' + summary.half_day + ')'
                    : item.level}
                </CustomText>
              </Pressable>
            );
          })}
        </View>

        {/* Loading Indicator */}
        {loading ? (
          <View style={styles.loadingContainer}>
            <ActivityIndicator
              size="large"
              color={LightThemeColors.titleColor}
            />
            <CustomText style={styles.loadingText}>
              Loading attendance...
            </CustomText>
          </View>
        ) : (
          /* Attendance List */
          <FlatList
            style={styles.flateList}
            data={filteredData}
            keyExtractor={(item, index) => `${item.id}-${index}`}
            renderItem={renderItem}
            showsVerticalScrollIndicator={false}
            contentContainerStyle={
              filteredData.length === 0
                ? styles.contentContainerStyleEmpty
                : styles.contentContainerStyle
            }
            ListEmptyComponent={renderEmptyComponent}
          />
        )}

        {/* MonthPicker - Last 3 months only */}
        {showPicker && (
          <MonthPicker
            onChange={onMonthChange}
            value={currentMoment ? currentMoment.toDate() : new Date()}
            minimumDate={minDate}
            maximumDate={maxDate}
            locale="en"
            mode="short"
          />
        )}
      </View>
    </SafeAreaView>
  );
};

export default EmployeeAttendanceView;
