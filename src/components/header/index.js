import React from 'react';
import FastImage from '@d11/react-native-fast-image';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Image,
  Pressable,
} from 'react-native';
import { scale } from 'react-native-size-matters';
import { useNavigation } from '@react-navigation/native';
import Icons from '../Icons/Icons';
import {
  SCREEN_WIDTH,
  POPPINS_SEMIBOLD,
  FONT_SIZE_SM,
  POPPINS_REGULAR,
  FONT_SIZE_XXS,
} from '../../config/Constants';
import { CustomText } from '../global/CustomComponents';
import { Colors } from '../../config/Colors';

const Header = ({
  style,
  back,
  rightComponent,
  leftComponent,
  headerBg,
  iconColor,
  onPress,
  title,
  profileImage,
  textColor,
  name,
  date,
  time,
  employeeId,
  imageOnPress,
}) => {
  const navigation = useNavigation();

  const LeftView = () => (
    <View style={[styles.view]}>
      {leftComponent && (
        <View style={styles.profileView}>
          <Pressable onPress={imageOnPress}>
            <FastImage
              source={
                profileImage
                  ? {
                      uri: profileImage,
                      priority: FastImage.priority.high,
                    }
                  : require('../../assets/images/Container.png')
              }
              style={styles.profileImage}
              resizeMode="contain"
            />
          </Pressable>
          <View>
            <CustomText style={[styles.welcomenText, { color: textColor }]}>
              Welcome
            </CustomText>
            <CustomText style={[styles.name, { color: textColor }]}>
              {name}
            </CustomText>
            <CustomText style={[styles.date, { color: textColor }]}>
              {employeeId}
            </CustomText>
          </View>
        </View>
      )}
      {back && (
        <TouchableOpacity
          style={styles.buttonBack}
          onPress={() => {
            navigation.goBack(), onPress?.();
          }}
        >
          <Icons
            iconType={'Ionicons'}
            name="chevron-back"
            size={scale(22)}
            color={iconColor}
          />
          <Text style={[styles.title, { color: iconColor }]}>{title}</Text>
        </TouchableOpacity>
      )}
    </View>
  );
  const RightView = () => (
    <>
      {rightComponent && (
        <View style={styles.rightViewCard}>
          <CustomText style={[styles.nameDay, { color: textColor }]}>
            {date}
          </CustomText>
          <CustomText style={[styles.time, { color: textColor }]}>
            {time}
          </CustomText>
        </View>
      )}
    </>
  );

  return (
    <View style={[styles.header, style, { backgroundColor: headerBg }]}>
      <LeftView />
      <RightView />
    </View>
  );
};

export default Header;

const styles = StyleSheet.create({
  header: {
    height: scale(120),
    justifyContent: 'space-between',
    alignItems: 'center',
    flexDirection: 'row',
    paddingHorizontal: SCREEN_WIDTH * 0.05,
  },
  view: {
    alignItems: 'center',
    flexDirection: 'row',
  },

  titleView: {
    alignSelf: 'center',
    alignItems: 'center',
  },
  titletext: {
    fontSize: scale(18),
    fontFamily: POPPINS_SEMIBOLD,
    width: SCREEN_WIDTH * 0.45,
  },
  rightView: {
    justifyContent: 'flex-end',
  },
  rowView: {
    flexDirection: 'row',
    alignItems: 'center',
    marginRight: 10,
  },
  buttonBack: {
    flexDirection: 'row',
    alignItems: 'center',
    columnGap: scale(5),
  },
  title: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_SM,
  },
  profileImage: {
    height: scale(50),
    width: scale(50),
    borderRadius: scale(25),
  },
  profileView: {
    flexDirection: 'row',
    alignItems: 'center',
    columnGap: scale(14),
  },
  name: {
    fontFamily: POPPINS_REGULAR,
    fontSize: FONT_SIZE_SM,
    width: scale(130),
    lineHeight: scale(20),
    textTransform: 'capitalize',
  },
  welcomenText: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_SM,
    width: scale(130),
  },
  date: {
    fontFamily: POPPINS_REGULAR,
    fontSize: FONT_SIZE_XXS,
    width: scale(160),
  },
  nameDay: {
    fontFamily: POPPINS_REGULAR,
    fontSize: FONT_SIZE_SM,
  },
  time: {
    fontFamily: POPPINS_REGULAR,
    fontSize: FONT_SIZE_XXS,
  },
  rightViewCard: {
    borderWidth: 1,
    alignItems: 'center',
    paddingHorizontal: scale(8),
    paddingVertical: scale(4),
    borderRadius: scale(4),
    borderColor: Colors.white,
  },
});
