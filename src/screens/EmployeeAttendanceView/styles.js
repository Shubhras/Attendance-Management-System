import { StyleSheet } from 'react-native';
import {
  FONT_SIZE_LG,
  FONT_SIZE_MD,
  FONT_SIZE_SM,
  FONT_SIZE_XS,
  FONT_SIZE_XXS,
  POPPINS_BOLD,
  POPPINS_MEDIUM,
  POPPINS_SEMIBOLD,
  SCREEN_WIDTH,
  STANDARD_FLEX,
  STANDARD_SPACING,
} from '../../config/Constants';
import { scale } from 'react-native-size-matters';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex: STANDARD_FLEX,

  },
  flateList: {
    marginTop: STANDARD_SPACING * 4
  },
  monthNavigator: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    columnGap: STANDARD_SPACING * 3,
    paddingVertical: STANDARD_SPACING * 4
  },
  monthText: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_MD
  },
  filter: {
    flexDirection: 'row',
    columnGap: scale(5),
    marginHorizontal: SCREEN_WIDTH * 0.05
  },
  filterButton: {
    borderWidth: 1,
    height: scale(26),
    width: scale(60),
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: scale(5)

  },
  filterText: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_XXS,
    lineHeight: scale(14)
  },
  contentContainerStyle: {
    paddingHorizontal: SCREEN_WIDTH * 0.05,
    rowGap: scale(12),
    paddingBottom: scale(12)
  }

});
