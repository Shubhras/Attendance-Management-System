import React, { useState } from 'react';
import { FlatList, Pressable, View } from 'react-native';
import styles from './styles.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import DayDetailsCard from '../../components/cards/DayDetailsCard/index.js';
import { scale } from 'react-native-size-matters';
import { CustomText } from '../../components/global/CustomComponents.js';
import Icons from '../../components/Icons/Icons.js';
import MonthPicker from 'react-native-month-year-picker';

const yearData = [
  {
    month: "January 2025",
    data: [
      { day: "Wed", date: "2025-01-01", time_in: null, time_out: null, status: "Absent" },
      { day: "Thu", date: "2025-01-02", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Fri", date: "2025-01-04", time_in: "08:07 AM", time_out: "17:02 PM", status: "Pending" },
      { day: "Sat", date: "2025-01-05", time_in: "08:05 AM", time_out: "17:00 PM", status: "Absent" },
      { day: "Mon", date: "2025-01-07", time_in: "08:07 AM", time_out: "17:02 PM", status: "Half day" },
    ]
  },
  {
    month: "February 2025",
    data: [
      { day: "Sat", date: "2025-02-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Sun", date: "2025-02-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "March 2025",
    data: [
      { day: "Sat", date: "2025-03-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Sun", date: "2025-03-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "April 2025",
    data: [
      { day: "Tue", date: "2025-04-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Wed", date: "2025-04-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "May 2025",
    data: [
      { day: "Thu", date: "2025-05-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Fri", date: "2025-05-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "June 2025",
    data: [
      { day: "Sun", date: "2025-06-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Mon", date: "2025-06-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "July 2025",
    data: [
      { day: "Tue", date: "2025-07-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Wed", date: "2025-07-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "August 2025",
    data: [
      { day: "Fri", date: "2025-08-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Sat", date: "2025-08-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "September 2025",
    data: [
      { day: "Mon", date: "2025-09-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Tue", date: "2025-09-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "October 2025",
    data: [
      { day: "Wed", date: "2025-10-01", time_in: "08:08 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Thu", date: "2025-10-02", time_in: null, time_out: null, status: "Absent" },
      { day: "Fri", date: "2025-10-03", time_in: "08:02 AM", time_out: null, status: "Pending" },
      { day: "Sat", date: "2025-10-04", time_in: "08:05 AM", time_out: "16:27 PM", status: "Present" },
      { day: "Mon", date: "2025-10-06", time_in: "08:02 AM", time_out: null, status: "Half day" },
    ]
  },
  {
    month: "November 2025",
    data: [
      { day: "Sat", date: "2025-11-01", time_in: "08:05 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Sun", date: "2025-11-02", time_in: null, time_out: null, status: "Absent" },
    ]
  },
  {
    month: "December 2025",
    data: [
      { day: "Mon", date: "2025-12-01", time_in: "08:00 AM", time_out: "17:00 PM", status: "Present" },
      { day: "Tue", date: "2025-12-02", time_in: null, time_out: null, status: "Absent" },
    ]
  }
];





const Filters = [
  { key: 'all', level: 'All' },
  { key: 'pending', level: 'Pending' },
  { key: 'present', level: 'Present' },
  { key: 'absent', level: 'Absent' },
  { key: 'half_day', level: 'Half-day' },
];

const AttendanceScreen = () => {
  const [showPicker, setShowPicker] = useState(false);
  const [selectedMonthIndex, setSelectedMonthIndex] = useState(0);
  const [selectedFilter, setSelectedFilter] = useState('all');

  const currentMonth = yearData[selectedMonthIndex];
  const currentMonthData = currentMonth.data;

  const filteredData = currentMonthData.filter((item) => {
    if (selectedFilter === 'all') return true;
    if (selectedFilter === 'half_day') return item.status?.toLowerCase() === 'half day';
    return item.status?.toLowerCase() === selectedFilter;
  });

  const handlePrevMonth = () => {
    if (selectedMonthIndex > 0) setSelectedMonthIndex(selectedMonthIndex - 1);
  };

  const handleNextMonth = () => {
    if (selectedMonthIndex < yearData.length - 1) setSelectedMonthIndex(selectedMonthIndex + 1);
  };

  const showMonthPickerHandler = () => setShowPicker(true);

  const onMonthChange = (event, newDate) => {
    if (event === 'dateSetAction' && newDate) {
      const monthIndex = newDate.getMonth(); // 0 = Jan, 1 = Feb
      setSelectedMonthIndex(monthIndex);
    }
    setShowPicker(false);
  };

  const renderItem = ({ item }) => (
    <DayDetailsCard
      status={item?.status}
      time_in={item?.time_in}
      // time_out={item?.time_out}
      date={item?.date}
      day={item?.day}
    />
  );

  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={LightThemeColors.titleColor}
      barStyle="white"
    >
      <View style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
        <Header
          back
          title="John Doe"
          headerBg={LightThemeColors.titleColor}
          iconColor={Colors.white}
          style={{ height: scale(50) }}
        />

        {/* Month Navigation */}
        <View style={styles.monthNavigator}>
          <Pressable onPress={handlePrevMonth} disabled={selectedMonthIndex === 0}>
            <Icons
              name="chevron-back-sharp"
              iconType="Ionicons"
              size={scale(22)}
              color={selectedMonthIndex === 0 ? Colors.gray : Colors.black}
            />
          </Pressable>

          <Pressable onPress={showMonthPickerHandler}>
            <CustomText style={styles.monthText}>{currentMonth.month}</CustomText>
          </Pressable>

          <Pressable onPress={handleNextMonth} disabled={selectedMonthIndex === yearData.length - 1}>
            <Icons
              name="chevron-forward-sharp"
              iconType="Ionicons"
              size={scale(22)}
              color={selectedMonthIndex === yearData.length - 1 ? Colors.gray : Colors.black}
            />
          </Pressable>
        </View>

        {/* Filters */}
        <View style={styles.filter}>
          {Filters.map((item) => {
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
                  {item.level}
                </CustomText>
              </Pressable>
            );
          })}
        </View>

        {/* Attendance List */}
        <FlatList
          style={styles.flateList}
          data={filteredData}
          keyExtractor={(item, index) => index.toString()}
          renderItem={renderItem}
          showsVerticalScrollIndicator={false}
          contentContainerStyle={styles.contentContainerStyle}
        />

        {/* Month Picker */}
        {/* {showPicker && (
          <MonthPicker
            onChange={onMonthChange}
            value={new Date()} // initial value doesn't matter, month is selected via index
            minimumDate={new Date(2020, 0)}
            maximumDate={new Date(2030, 11)}
            locale="en"
          />
        )} */}

        {showPicker && (
          <MonthPicker
            onChange={onMonthChange}
            value={new Date()}
            minimumDate={(() => {
              const today = new Date();
              const lastThree = new Date(today.getFullYear(), today.getMonth() - 2, 1);
              return lastThree;
            })()}
            maximumDate={new Date()}
            locale="en"
            mode="short"
          />
        )}
      </View>
    </CustomSafeAreaView>
  );
};

export default AttendanceScreen;










