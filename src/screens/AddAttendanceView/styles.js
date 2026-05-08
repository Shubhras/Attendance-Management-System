import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_MD,
  FONT_SIZE_SM,
  FONT_SIZE_XS,
  POPPINS_MEDIUM,
  POPPINS_REGULAR,
  SCREEN_WIDTH,
} from '../../config/Constants';
import { Colors, LightThemeColors } from '../../config/Colors';

// Exporting style
export default StyleSheet.create({
  mainwrapper: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    rowGap: scale(15),
  },
  imageContainer: {
    position: 'relative',
    overflow: 'hidden',
    alignSelf: 'center',
    alignItems: 'center',
    justifyContent: 'flex-end',
    height: scale(100),
    borderRadius: scale(50),
    marginTop: scale(20),
    backgroundColor: Colors.grey,
  },
  image: {
    flex: 1,
    aspectRatio: 1,
    width: null,
    height: null,
  },
  title: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD,
     marginBottom: scale(5),
  },
  fingerTitle: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD,
  },
  titleSuccess: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD,
    marginTop: scale(25),
  },
  subTitle: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD,
  },
  titlePIN: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD,
    marginTop: scale(30),
  },
  scannig: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  indicatorText: {
    fontFamily: POPPINS_REGULAR,
    fontSize: FONT_SIZE_SM,
  },
  button: {
    width: SCREEN_WIDTH * 0.4,
    borderWidth: scale(1),
    borderColor: LightThemeColors.titleColor,
  },
  buttonView: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    width: SCREEN_WIDTH * 0.9,
    alignSelf: 'center',
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
  },
});
