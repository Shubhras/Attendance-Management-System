
import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
// import {
//   FONT_SIZE_XS,
//   OPEN_SANS_BOLD,
//   OPEN_SANS_MEDIUM,
//   OPEN_SANS_REGULAR,
//   OPEN_SANS_SEMIBOLD,
//   STANDARD_SPACING,
//   STANDARD_TEXT_INPUT_HEIGHT,
// } from '../../../constants/Constants';
import { Colors } from '../../../config/Colors';
import { FONT_SIZE_XS, POPPINS_BOLD, POPPINS_MEDIUM, POPPINS_REGULAR, POPPINS_SEMIBOLD, STANDARD_BORDER_RADIUS, STANDARD_SPACING, STANDARD_TEXT_INPUT_HEIGHT } from '../../../config/Constants';

// Exporting style
export default StyleSheet.create({
  label: {
    marginBottom: STANDARD_SPACING,
    paddingHorizontal: STANDARD_SPACING * 0.5,
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_XS,
  },
  textInputWrapper: {
    overflow: 'hidden',
    position: 'relative',
    justifyContent: 'space-between',
    flexDirection: 'row',
    height: STANDARD_TEXT_INPUT_HEIGHT,
    borderColor: Colors.greyDark,
    borderWidth: 1,
    borderRadius: STANDARD_BORDER_RADIUS
  },
  textInput: {
    textAlignVertical: 'center',
    flex: 0.9,
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_XS,
    paddingLeft: STANDARD_SPACING * 3,
  },
  textInputIconWrapper: {
    alignItems: 'center',
    justifyContent: 'center',
    // position: 'absolute',
    width: STANDARD_TEXT_INPUT_HEIGHT,
    height: STANDARD_TEXT_INPUT_HEIGHT,
  },
  textInputIconWrapperRight: {
    alignItems: 'center',
    justifyContent: 'center',
    width: STANDARD_TEXT_INPUT_HEIGHT * 0.5,
    height: STANDARD_TEXT_INPUT_HEIGHT,
  },
  textCountryWrapper: {
    right: scale(5),
    alignItems: 'center',
    justifyContent: 'center',
    width: STANDARD_TEXT_INPUT_HEIGHT * 0.75,
    height: STANDARD_TEXT_INPUT_HEIGHT,
  },
  Countrylabel: {
    fontFamily: POPPINS_BOLD,
    fontSize: FONT_SIZE_XS,
  },
  textInputIconWrapperWithRightZero: {
    right: 0,
  },
  errorContainer: {
    // height: scale(18),
    paddingLeft: STANDARD_SPACING * 0.5,

    marginTop: scale(3),
  },
  errorText: {
    color: Colors.red,
    fontSize: scale(10),
    fontFamily: POPPINS_REGULAR,
  },
  errorTextSucess: {
    color: Colors.red,
    fontSize: scale(10),
    fontFamily: POPPINS_REGULAR,
  },
});
