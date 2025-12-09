import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_MD,
  FONT_SIZE_SM,
  FONT_SIZE_XS,
  FONT_SIZE_XXS,
  POPPINS_SEMIBOLD,
  SCREEN_WIDTH,
  STANDARD_BORDER_RADIUS,
  STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE,
  STANDARD_FLEX,
  STANDARD_MY_PROFILE_PHOTO_WRAPPER_SIZE,
  STANDARD_SPACING,
  STANDARD_TEXT_INPUT_HEIGHT,
} from '../../config/Constants';
import { Colors } from '../../config/Colors';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex: STANDARD_FLEX,
  },
  logoContainer: {
    alignItems: 'center',
  },
  profilePhotoWrapper: {
    position: 'relative',
    overflow: 'hidden',
    alignSelf: 'center',
    alignItems: 'center',
    justifyContent: 'flex-end',
    height: scale(100),
    borderRadius: scale(50),
    marginTop: scale(30),
  },
  profileImage: {
    flex: 1,
    aspectRatio: 1,
    width: null,
    height: null,
    resizeMode: 'contain',
  },
  marginBottom: {
    marginBottom: STANDARD_SPACING * 10,
  },
  logoWrapper: {
    alignItems: 'center',
    justifyContent: 'center',
    height: STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE * 1.8,
    width: STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE * 3,
    backgroundColor: 'red',
  },
  logoImage: {
    height: scale(100),
    width: scale(100),
    resizeMode: 'cover',
    borderRadius: scale(50),
  },
  changeText: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_SEMIBOLD,
  },
  buttonWrapper: {
    position: 'absolute',
    bottom: scale(40),
    alignSelf: 'center',
    width: SCREEN_WIDTH * 0.85,
  },
  row: {
    marginHorizontal: SCREEN_WIDTH * 0.08,
    flexDirection: 'row',
    alignItems: 'center',
  },
  title: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_MD,
  },
  value: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_SM,
    textTransform: 'capitalize',
  },
  emailText: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_SM,
  },
});
