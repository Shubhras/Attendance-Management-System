import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import { FONT_SIZE_MD, POPPINS_SEMIBOLD } from '../../../config/Constants';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex:1,
    width: '100%',
    alignItems: 'center',
    justifyContent: 'center',
  },
  lottieViewWrapper: {
    height: scale(150),
  },
  image:{
    flex:1,
    height: null,
    width: null,
    aspectRatio: 1,
  },
  message: {
    fontFamily: POPPINS_SEMIBOLD,
    fontSize: FONT_SIZE_MD,
    textAlign: 'center',
    marginTop: scale(15),
    marginHorizontal: scale(15),
  },
});
