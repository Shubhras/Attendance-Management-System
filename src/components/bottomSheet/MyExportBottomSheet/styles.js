import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_MD,
  FONT_SIZE_SM,
  FONT_SIZE_XS,
  POPPINS_MEDIUM,
  POPPINS_REGULAR,
  SCREEN_WIDTH,
} from '../../../config/Constants';
import { LightThemeColors } from '../../../config/Colors';

// Exporting style
export default StyleSheet.create({
  mainwrapper: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    rowGap: scale(25)
  },
  image: {
    width: scale(80),
    height: scale(80),
    marginTop: scale(30)
  },
  title: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD,

  },
  titleSuccess: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD,
    marginTop: scale(25)


  },
  subTitle: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_XS,
  },
  titlePIN: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD,
    marginTop: scale(30)

  },
  scannig: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  indicatorText: {
    fontFamily: POPPINS_REGULAR,
    fontSize: FONT_SIZE_SM
  },
  button: {
    width: SCREEN_WIDTH * 0.4,
    borderWidth: scale(1),
    borderColor: LightThemeColors.titleColor
  },
  buttonView: {
     width: SCREEN_WIDTH * 0.9,
     justifyContent:'center',
     alignItems:'center'
   },
  textInputWrapper: {
    width: SCREEN_WIDTH * 0.9,
  },
  indicator: {
    backgroundColor: '#C5C5C7',
    borderRadius: scale(10),
    width: scale(36),
    height: scale(5),
  },
  card: {
    alignItems: 'center',
    rowGap: scale(10),
    marginTop:scale(50)  }
});
