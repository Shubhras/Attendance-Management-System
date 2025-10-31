
import { StyleSheet } from "react-native";
import { FONT_SIZE_SM, FONT_SIZE_XS, FONT_SIZE_XXS, POPPINS_MEDIUM, POPPINS_REGULAR, SCREEN_WIDTH, STANDARD_BORDER_RADIUS } from "../../../config/Constants";
import { scale } from "react-native-size-matters";

export default StyleSheet.create({
  card: {
    width: SCREEN_WIDTH * 0.4,
    borderWidth: 1,
    borderColor: '#E5E5E5',
    minHeight: scale(110),
    padding: scale(12),
    borderRadius: STANDARD_BORDER_RADIUS * 2,
    backgroundColor: '#fff',
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 2,
    justifyContent: 'space-between',
    rowGap: scale(20)
  },
  icon: {
    width: scale(40),
    height: scale(40),
  },
  textView: {
  },
  title: {
    fontSize: FONT_SIZE_XS,
    fontFamily: POPPINS_MEDIUM

  },
  subtitle: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_REGULAR
  },
});
