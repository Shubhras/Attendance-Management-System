import { StyleSheet } from 'react-native';
import { scale } from 'react-native-size-matters';
import {
  POPPINS_MEDIUM,
  FONT_SIZE_SM,
  SCREEN_HEIGHT,
  SCREEN_WIDTH,
  STANDARD_BORDER_RADIUS,
  STANDARD_BORDER_WIDTH,
} from '../../config/Constants'
import { Colors, LightThemeColors } from '../../config/Colors';

const styles = StyleSheet.create({
  modalContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
  },
  topline: {
    backgroundColor: Colors.grey,
    height: scale(6),
    width: SCREEN_WIDTH * 0.25,
    borderRadius: STANDARD_BORDER_RADIUS * 1,
    position: 'absolute',
    marginTop: scale(10),
    top: 0,
  },
  innerModal: {
    height: SCREEN_HEIGHT * 0.3,
    backgroundColor: 'white',
    padding: 20,
    borderTopLeftRadius: STANDARD_BORDER_RADIUS * 4,
    borderTopRightRadius: STANDARD_BORDER_RADIUS * 4,
    width: SCREEN_WIDTH,
    alignItems: 'center',
    justifyContent: 'center',
    bottom: 0,
    position: 'absolute',
  },
  SvgWrapper: {
    width: SCREEN_WIDTH,
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  SvgContainer: {
    height: SCREEN_HEIGHT * 0.1,
    width: SCREEN_WIDTH * 0.2,
    borderWidth: STANDARD_BORDER_WIDTH * 2,
    borderColor: LightThemeColors.titleColor,
    borderRadius: STANDARD_BORDER_RADIUS * 3,
    justifyContent: 'center',
    alignItems: 'center',
  },
  titletxt: {
    fontSize: FONT_SIZE_SM,
    fontFamily: POPPINS_MEDIUM,
    fontWeight: '700',
    color: Colors.black,
    lineHeight: scale(25),
  },
  optionButton: {
    marginVertical: 10,
    padding: 10,
    borderRadius: 5,
    backgroundColor: '#3498db',
    alignItems: 'center',
  },
  optionButtonText: {
    color: 'white',
  },
});
export default styles;
