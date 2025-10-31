import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  FONT_SIZE_LG,
  FONT_SIZE_MD,
  FONT_SIZE_SM,
  FONT_SIZE_XS,
  POPPINS_BOLD,
  POPPINS_MEDIUM,
  POPPINS_SEMIBOLD,
  SCREEN_WIDTH,
  STANDARD_FLEX,
  STANDARD_SPACING,
} from '../../config/Constants';
import { Colors } from '../../config/Colors';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex: STANDARD_FLEX,

  },
  profileWrapper: {
    marginTop: STANDARD_SPACING * 3,
    paddingHorizontal: SCREEN_WIDTH * 0.05,
    flexDirection: 'row',
    alignItems: 'center',
    columnGap: STANDARD_SPACING * 4,
    marginBottom: STANDARD_SPACING * 4,

  },
  imageWrapper: {
    width: scale(80),
    height: scale(80),
    borderRadius: scale(40),
    alignItems: 'center',
    justifyContent: 'center',

  },
  image: {
    width: '100%',
    height: '100%',
    resizeMode: 'cover',
    borderRadius: scale(40),
    borderWidth:scale(1),
    borderColor:Colors.grey
  },
  name: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_LG,
    lineHeight: scale(24),
    width: SCREEN_WIDTH * 0.6
  },
  id: {
    fontFamily: POPPINS_MEDIUM,
    fontSize: FONT_SIZE_XS
  },
  row: {
    marginHorizontal: SCREEN_WIDTH * 0.05,
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: STANDARD_SPACING * 2,

  },
  title: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_MD
  },
  value: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_SM,
    textDecorationLine: 'underline',
    width:SCREEN_WIDTH*0.5,
    textAlign:'right',
    textTransform:"capitalize"
  },
  valueWrapper: {
    // width:SCREEN_WIDTH*0.5
  },
  buttonWrapper: {
    marginHorizontal: SCREEN_WIDTH * 0.05,
    marginTop: STANDARD_SPACING * 5
  }
});
