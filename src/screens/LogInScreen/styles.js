import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import { widthPercentageToDP as wp, } from 'react-native-responsive-screen';
import { FONT_SIZE_SM, FONT_SIZE_XS, FONT_SIZE_XXS, POPPINS_MEDIUM, POPPINS_SEMIBOLD, SCREEN_WIDTH, STANDARD_BORDER_RADIUS, STANDARD_BUTTON_HEIGHT, STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE, STANDARD_FLEX, STANDARD_SPACING, STANDARD_TEXT_INPUT_HEIGHT } from '../../config/Constants';
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
    height: STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE * 3.2,
    width: STANDARD_CATEGORY_IMAGE_WRAPPER_SIZE * 3,   // ✅ Add width
  },
  logoImage: {
    height: '40%',
    width: '75%',
    resizeMode: 'contain',
  },
  
  textInputWrapper: {
     marginHorizontal: SCREEN_WIDTH * 0.06,
    marginTop:scale(15)
  },
  labelText: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_XS,
    color: Colors.boysenberry
  },
  labelTextWrapper: {
    // width: wp('88%'),
    marginHorizontal: SCREEN_WIDTH * 0.06,

    // alignSelf: 'center',
    paddingBottom: STANDARD_SPACING * 1.5
  },
  linkWrapper: {
    // width: wp('88%'),
    marginHorizontal: SCREEN_WIDTH * 0.06,
    alignItems: 'flex-end'
  },
  changeLoginWrapper: {
    // width: wp('88%'),
    marginHorizontal: SCREEN_WIDTH * 0.06,
    flexDirection: 'row',
    columnGap: scale(4)
  },
  changeText: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_SEMIBOLD
  },
  buttonWrapper: {
    marginTop: scale(20),
    marginHorizontal: SCREEN_WIDTH * 0.06,    // width: wp('88%'),
  },

  label: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_SM,
  },
  button: {
    alignItems: 'center',
    justifyContent: 'center',
    height: STANDARD_BUTTON_HEIGHT,
    borderRadius: STANDARD_BUTTON_HEIGHT * 0.2,
    paddingHorizontal: scale(15),
  },
});