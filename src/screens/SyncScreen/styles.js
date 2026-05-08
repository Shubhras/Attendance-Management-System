import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import { Colors } from '../../config/Colors';
import {
  FONT_SIZE_XS,
  POPPINS_BOLD,
  POPPINS_REGULAR,
  STANDARD_FLEX,
  STANDARD_SPACING
} from '../../config/Constants';

// Exporting style
export default StyleSheet.create({
  mainWrapper: {
    flex: STANDARD_FLEX,
  },
  imageBackground: {
    position: 'relative',
    flex: STANDARD_FLEX,
    backgroundColor: '#D61313',
  },
  imageBackgroundOverlay: {
    position: 'absolute',
    left: 0,
    top: 0,
    justifyContent: 'center',
    alignItems: 'center',
    width: '100%',
    height: '100%',
  },
  logoWrapper: {
    width: scale(150),
    aspectRatio: 1,
    borderRadius: scale(75),
    marginBottom: STANDARD_SPACING * 4,
    backgroundColor: Colors.white
  },
  logo: {
    width: null,
    height: null,
    flex: STANDARD_FLEX,
    resizeMode: 'contain',
  },

  // 🔥 NEW ADDITIONS START

  syncText: {
    marginTop: STANDARD_SPACING,
    color: Colors.white,
    fontSize: FONT_SIZE_XS,
    fontFamily: POPPINS_REGULAR,
    textAlign: 'center',
  },

  progressBar: {
    width: '70%',
    height: scale(8),
    backgroundColor: 'rgba(255,255,255,0.3)',
    borderRadius: scale(10),
    marginTop: STANDARD_SPACING,
    overflow: 'hidden',
  },

  progressFill: {
    height: '100%',
    backgroundColor: Colors.white,
  },

  percent: {
    marginTop: STANDARD_SPACING / 2,
    color: Colors.white,
    fontSize: FONT_SIZE_XS,
    fontFamily: POPPINS_BOLD,
  },

  errorText: {
    color: Colors.white,
    marginTop: STANDARD_SPACING,
    fontSize: FONT_SIZE_XS,
    fontFamily: POPPINS_BOLD,
    textAlign: 'center',
  },

  button: {
    marginTop: STANDARD_SPACING * 2,
    width: scale(160),
  },

  // 🔥 NEW ADDITIONS END
});
