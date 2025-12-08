import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_SM,
  FONT_SIZE_XS,
  POPPINS_BOLD,
  POPPINS_MEDIUM,
  POPPINS_REGULAR,
  STANDARD_COUPON_CARD_BRAND_IMAGE_WRAPPER_SIZE,
  STANDARD_FLEX,
  STANDARD_OVERALL_RATING_CARD_HEIGHT,
  STANDARD_SPACING
} from '../../../config/Constants';

// Creating stylesheets
export default StyleSheet.create({
  card: {
    position: 'relative',
    minHeight: STANDARD_OVERALL_RATING_CARD_HEIGHT,
    borderRadius: STANDARD_OVERALL_RATING_CARD_HEIGHT * 0.1,
    shadowColor: '#6c757d',
    shadowOffset: {width: scale(0), height: scale(7.5)},
    shadowOpacity: 0.15,
    shadowRadius: scale(5),
    elevation: scale(7.5),
  },
  imageWrapper: {
    height: STANDARD_OVERALL_RATING_CARD_HEIGHT * 0.5,
    alignItems: 'center',
    justifyContent: 'flex-end',
  },
  brandImageWrapper: {
    height: scale(80),
    aspectRatio: 1,
    padding: STANDARD_SPACING,
    overflow: 'hidden',
    borderRadius: STANDARD_COUPON_CARD_BRAND_IMAGE_WRAPPER_SIZE * 0.5,
  },
  brandImage: {
    width: null,
    height: null,
    flex: STANDARD_FLEX,
    resizeMode: 'cover',
  },
  detailsBackgroundImageWrapper: {
    height: STANDARD_OVERALL_RATING_CARD_HEIGHT * 0.5,
    borderBottomLeftRadius: STANDARD_OVERALL_RATING_CARD_HEIGHT * 0.1,
    borderBottomRightRadius: STANDARD_OVERALL_RATING_CARD_HEIGHT * 0.1,
    overflow: 'hidden',
    position: 'relative',
  },
  detailsWrapper: {
    position: 'absolute',
    width: '100%',
    height: '100%',
    left: scale(10),
    top: 0,
    alignItems: 'flex-start',
    justifyContent: 'center',
  },
  title: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_SM,
  },
  validUpto: {
    fontFamily: POPPINS_REGULAR,
    fontSize: FONT_SIZE_XS,
  },
  countItem:{
    fontFamily: POPPINS_BOLD,
    fontSize: FONT_SIZE_XS
  }
});
