import { useFocusEffect } from '@react-navigation/native';
import React, { useCallback, useRef, useState } from 'react';
import { ScrollView, View } from 'react-native';
import { scale } from 'react-native-size-matters';
import { useSelector } from 'react-redux';
import { HomeCount } from '../../api/auth.js';
import { Indicators } from '../../components/apploader';
import MyExportBottomSheet from '../../components/bottomSheet/MyExportBottomSheet/index.js';
import AccessCard from '../../components/cards/AccessCard/index.js';
import { CustomText } from '../../components/global/CustomComponents.js';
import CustomSafeAreaView from '../../components/global/CustomSafeAreaView.tsx';
import Header from '../../components/header/index.js';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import { SCREEN_WIDTH } from '../../config/Constants.js';
import { getHomeData } from '../../data/Homedata.js';
import { FlatGrid } from 'react-native-super-grid';
import styles from './styles.js';

const HomeScreen = ({ navigation }) => {
  const user = useSelector(state => state.users.users);
  console.log('xxxxxxxxxxxxxxxxx', user);

  const token = user?.access_token;
  const userdata = user?.user;
  const bottomSheetRef = useRef(null);
  const [countData, setCountData] = useState({
    employees_count: 0,
    machines_count: 0,
    contractor_count: 0,
    attendance_count: 0,
    current_date: '',
    fingerprint_false_count: 0,
  });
  const [loading, setLoading] = useState(false);

  const HomeCountApi = useCallback(() => {
    setLoading(true);

    HomeCount(token)
      .then(res => {
        console.log('Home Count Response', res);
        if (res?.status && res?.data) {
          setCountData(res.data);
        }
        setLoading(false);
      })
      .catch(err => {
        setLoading(false);
        console.log('Home Count Error', err);
        showMessage({
          message: 'Error',
          description: err?.message || 'Something went wrong',
          type: 'danger',
        });
      });
  }, [token]); // Empty dependency array ensures the effect runs once on mount

  useFocusEffect(
    React.useCallback(() => {
      HomeCountApi();
    }, [HomeCountApi]), // Make sure to include HomeCountApi in dependencies
  );

  const homeData = getHomeData(countData);

  const ListHeader = () => {
    return (
      <View style={styles.sectionTitleWrapper}>
        <CustomText
          style={[
            styles.sectionTitle,
            { color: LightThemeColors.textHighContrast },
          ]}
        >
          Quick Access
        </CustomText>
      </View>
    );
  };

  return (
    <CustomSafeAreaView
      statusBarBackgroundColor={LightThemeColors.titleColor}
      barStyle="light-content"
      style={[styles.mainWrapper, { backgroundColor: Colors.white }]}
    >
      <Header
        headerBg={LightThemeColors.titleColor}
        iconColor={Colors.white}
        leftComponent
        textColor={Colors.white}
        profileImage={userdata?.photo}
        name={userdata?.name}
        employeeId={userdata?.employee_code}
        imageOnPress={() => {
          navigation.navigate('ProfileScreen');
        }}
      />
      {/* Flatgrid */}
      <FlatGrid
        itemDimension={scale(130)}
        data={homeData}
        style={styles.flatGrid}
        spacing={scale(15)}
        bounces={false}
        showsVerticalScrollIndicator={false}
        ListHeaderComponent={ListHeader}
        renderItem={({ item }) => (
          <AccessCard
            key={item?.id}
            title={item.title}
            subtitle={item.subtitle}
            categoryImage={item.icon}
            defaultSource={item.defaultSource}
            subtitleValue={item?.subtitleValue}
            onPress={() => {
              if (item?.onPress == 'MyExport') {
                bottomSheetRef.current?.expand();
              } else {
                navigation.navigate(item?.onPress);
              }
            }}
          />
        )}
      />
      <MyExportBottomSheet
        sheetRef={bottomSheetRef}
        onCancel={() => {
          bottomSheetRef.current?.close();
        }}
        token={token}
      />
      {loading && <Indicators />}
    </CustomSafeAreaView>
  );
};

export default HomeScreen;
