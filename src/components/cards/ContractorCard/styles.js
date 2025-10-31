import { StyleSheet } from 'react-native';
import {
  FONT_SIZE_MD,
  FONT_SIZE_XS,
  FONT_SIZE_XXS,
  POPPINS_MEDIUM,
  POPPINS_REGULAR,
  POPPINS_SEMIBOLD,
  SCREEN_WIDTH,
  STANDARD_BORDER_RADIUS,
} from '../../../config/Constants';
import { scale } from 'react-native-size-matters';

export default StyleSheet.create({
  card: {
    borderWidth: 1,
    borderColor: '#E5E5E5',
    minHeight: scale(80),
    padding: scale(10),
    borderRadius: STANDARD_BORDER_RADIUS * 2,
    backgroundColor: '#fff',
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 2,
    flexDirection: 'row',
    alignItems: 'center',
    columnGap: scale(10)

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
    width: SCREEN_WIDTH * 0.5
  },
  subtitle: {
    fontSize: FONT_SIZE_XXS,
    fontFamily: POPPINS_REGULAR,
  },
  downloadButton: {
    height: scale(40),
    width: scale(40),
    padding: scale(5),
    borderRadius: scale(20),
    alignItems: 'center',
    justifyContent: 'center'
  }
});
