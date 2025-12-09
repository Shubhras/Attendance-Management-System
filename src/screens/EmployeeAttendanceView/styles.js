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
import { Colors } from '../../config/Colors';

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
    width: scale(75),
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: scale(5)

  },
  filterText: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_XXS,
    lineHeight: scale(14)
  },
  contentContainerStyleEmpty: {
    flex: 1,
    alignItems: 'center',
  },
  contentContainerStyle: {
    paddingHorizontal: SCREEN_WIDTH * 0.05,
    rowGap: scale(10),
    paddingBottom: scale(10),
  },
  loadingContainer:{
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  emptyContainer:{
    // flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
   imageContainer: {
    position: 'relative',
    overflow: 'hidden',
    alignSelf: 'center',
    alignItems: 'center',
    justifyContent: 'flex-end',
    height: scale(100),
    borderRadius: scale(50),
  },
  image: {
    flex: 1,
    aspectRatio: 1,
    width: null,
    height: null,
  },
});
