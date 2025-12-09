import { StatusBar, View } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';

// Functional component
const Statusbar = ({backgroundColor, ...props}) => {
    const insets = useSafeAreaInsets();
  // Returning
  return (
    <View style={[{paddingTop: insets.top, backgroundColor}]}>
      <StatusBar translucent backgroundColor={backgroundColor} {...props} />
    </View>
  );
};

// Exporting
export default Statusbar;
