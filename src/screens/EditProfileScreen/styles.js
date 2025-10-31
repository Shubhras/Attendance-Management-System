import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import { FONT_SIZE_MD, FONT_SIZE_SM, FONT_SIZE_XS, FONT_SIZE_XXS, POPPINS_SEMIBOLD, SCREEN_WIDTH, STANDARD_BORDER_RADIUS, STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE, STANDARD_FLEX, STANDARD_SPACING, STANDARD_TEXT_INPUT_HEIGHT } from '../../config/Constants';
import { Colors } from '../../config/Colors';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex: STANDARD_FLEX,
  },
  logoContainer: {
    alignItems: 'center',
  },
  logoWrapper: {
    alignItems: 'center',
    justifyContent: 'center',
    height: STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE * 1.8,
    width: STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE * 3,
  },
  logoImage: {
    height: scale(100),
    width: scale(100),
    resizeMode: 'cover',
    borderRadius: scale(50)
  },
  changeText: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_SEMIBOLD
  },
  buttonWrapper: {
    marginTop: scale(20),
    marginHorizontal: SCREEN_WIDTH * 0.06,
  },
  textInputWrapper: {
    marginHorizontal: SCREEN_WIDTH * 0.06,
    marginTop: scale(8)
  },
  editButton: {
    position: 'absolute',
    bottom: scale(25),
    right: scale(80),
    borderRadius: scale(17),
    height: scale(35),
    width: scale(35),
    backgroundColor:Colors.grey,
    alignItems:'center',
    justifyContent:'center'
  }
});