import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_XS,
  FONT_SIZE_XXS,
  POPPINS_MEDIUM,
  POPPINS_REGULAR,
  SCREEN_WIDTH,
  STANDARD_BORDER_RADIUS,
} from '../../../config/Constants';

export default StyleSheet.create({
  card: {
    width: SCREEN_WIDTH * 0.4,
    borderWidth: 1,
    borderColor: '#E5E5E5',
    minHeight: scale(110),
    padding: scale(8),
    borderRadius: STANDARD_BORDER_RADIUS * 2,
    backgroundColor: '#fff',
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 2,
    justifyContent: 'space-between',
    rowGap: scale(5),
  },
  icon: {
    width: scale(40),
    height: scale(40),
    alignSelf: 'center',
    marginBottom: scale(8),
  },
  machineName: {
    fontSize: FONT_SIZE_XS,
    fontFamily: POPPINS_MEDIUM,
    lineHeight: scale(15),
  },
  managerName: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_MEDIUM,
    lineHeight: scale(15),
  },
  employeeCount: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_REGULAR,
    lineHeight: scale(15),
  },
  imageWrapper: {
    alignItems: 'center',
    borderRadius: scale(10),
    overflow: 'hidden',
  },
  image: {
    width: '100%',
    aspectRatio: 4 / 3,
    resizeMode: 'cover',
    borderRadius: scale(10),
  },
});
