import React, { useRef } from 'react';
import { ScrollView, View } from 'react-native';
import styles from './styles.js';
import { CustomText } from '../../components/global/CustomComponents.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import Header from '../../components/header/index.js';
import AccessCard from '../../components/cards/AccessCard/index.js';
import { SCREEN_WIDTH } from '../../config/Constants.js';
import { scale } from 'react-native-size-matters';
import MyExportBottomSheet from '../../components/bottomSheet/MyExportBottomSheet/index.js';
import { useSelector } from 'react-redux';

const data = [
  {
    id: 1,
    title: 'My Employee',
    subtitle: 'Total Employees',
    icon: require('../../assets/images/Attendance.png'),
    onPress: 'MyEmployeeScreen',
    subtitleValue: 100,
    param: " "

  },
  {
    id: 2,
    title: 'Add Attendance',
    subtitle: 'Today',
    subtitleValue: '',
    icon: require('../../assets/images/shift.png'),
    onPress: 'MyMachineScreen',
    param: " "


  },
  {
    id: 3,
    title: 'My Machine',
    subtitle: 'Total Machines',
    subtitleValue: 10,
    icon: require('../../assets/images/machine.png'),
    onPress: 'MyMachineScreen',
    param: "MyMachine"
  },
  {
    id: 4,
    title: 'My Export',
    subtitle: 'Date',
    subtitleValue: '27-10-2025',
    icon: require('../../assets/images/export.png'),
    onPress: 'MyExport',
    param: " "

  },
  {
    id: 5,
    title: 'Report',
    subtitle: 'Total Contractors',
    subtitleValue: 20,
    icon: require('../../assets/images/document.png'),
    param: " ",
    onPress: 'ContractorListScreen',

  },
];

const HomeScreen = ({ navigation }) => {
  const user = useSelector(state => state.users.users?.user);
  console.log('Home', user)
  const bottomSheetRef = useRef(null);

  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={LightThemeColors.titleColor}
      barStyle="white"
    >
      <View style={[styles.mainWrapper, { backgroundColor: Colors.white }]}>
        <Header
          //  back={true}
          // title={'Attandance'}
          headerBg={LightThemeColors.titleColor}
          iconColor={Colors.white}
          leftComponent
          textColor={Colors.white}
          profileImage={user?.photo}
          name={user?.name}
          employeeId={user?.id}
          imageOnPress={() => { navigation.navigate('ProfileScreen') }}

        />
        <View style={styles.titleView}>
          <CustomText style={[styles.title, { color: LightThemeColors.textHighContrast }]}>Quick Access</CustomText>
        </View>

        <ScrollView
          showsVerticalScrollIndicator={false}
          contentContainerStyle={{
            paddingVertical: scale(16),
            paddingHorizontal: SCREEN_WIDTH * 0.03,
          }}
        >
          {/* 2 cards per row */}
          <View style={styles.cardContainer}>
            {data.map(item => (
              <AccessCard
                key={item.id}
                title={item.title}
                subtitle={item.subtitle}
                icon={item.icon}
                subtitleValue={item?.subtitleValue}
                onPress={() => {
                  item?.onPress == 'MyExport' ?
                    bottomSheetRef.current?.expand()
                    : navigation.navigate(item?.onPress, { param: item?.param }
                    )
                }}
              />
            ))}
          </View>
        </ScrollView>
      </View>
      <MyExportBottomSheet
        sheetRef={bottomSheetRef}
        onCancel={() => { bottomSheetRef.current?.close() }}
      />
    </CustomSafeAreaView>
  );
};

export default HomeScreen;
