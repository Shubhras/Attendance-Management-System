import { StyleSheet } from 'react-native';
import {
  FONT_SIZE_XS,
  FONT_SIZE_XXS,
  POPPINS_MEDIUM,
  POPPINS_REGULAR,
  POPPINS_SEMIBOLD,
  SCREEN_WIDTH,
  STANDARD_BORDER_RADIUS,
} from '../../../config/Constants';
import { scale } from 'react-native-size-matters';
import { Colors } from '../../../config/Colors';

export default StyleSheet.create({
  card: {
    borderWidth: 1,
    borderColor: '#E5E5E5',
    minHeight: scale(60),
    padding: scale(10),
    borderRadius: STANDARD_BORDER_RADIUS * 2,
    backgroundColor: '#fff',
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 2,
    flexDirection: 'row',
    alignItems: 'center',
    columnGap: scale(10),
    justifyContent: 'space-between'

  },
  cardWrapper: {
    borderLeftWidth: 3,
    borderRadius: STANDARD_BORDER_RADIUS * 2,
  },
  icon: {
    width: scale(45),
    height: scale(45),
    alignSelf: 'center',
    marginBottom: scale(8),
    borderRadius: scale(22.5)
  },

  id: {
    fontSize: FONT_SIZE_XS,
    fontFamily: POPPINS_MEDIUM,
  },
  title: {
    fontSize: FONT_SIZE_XS,
    fontFamily: POPPINS_SEMIBOLD,
    width: SCREEN_WIDTH * 0.6
  },
  subtitle: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_REGULAR,
  },
  statusButton: {
    borderWidth: 1,
    height: scale(30),
    borderColor: Colors.greyDark,
    width: scale(83),
    // paddingVertical: scale(4),
    paddingHorizontal: scale(5),
    borderRadius: scale(5),
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between'
  },
  statusText: {
    fontFamily: POPPINS_REGULAR,
    fontSize: FONT_SIZE_XS,
    lineHeight: scale(15)

  }
});
