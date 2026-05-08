import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import { FONT_SIZE_MD, POPPINS_SEMIBOLD, SCREEN_HEIGHT } from '../../../config/Constants';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex:1,
    width: '100%',
    height: '70%',
    alignItems: 'center',
    justifyContent: 'center',
    position:'absolute',
    top:SCREEN_HEIGHT*0.3
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
