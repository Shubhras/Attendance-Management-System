import { View, ImageBackground, StatusBar } from 'react-native';
import * as Animatable from 'react-native-animatable';
import styles from './styles';

// Functional component
const Splash = () => {
  return (
    <Animatable.View
      style={[styles.mainWrapper]}
      delay={100}
      animation="fadeIn"
      easing="ease-in-out-sine"
      useNativeDriver={true}
    >
      {/* StatusBar */}
      <StatusBar
        translucent={true}
        backgroundColor="transparent"
        barStyle="light-content" // or "dark-content" based on your background
      />
      <View style={styles.imageBackground}>
        <View style={styles.imageBackgroundOverlay}>
          {/* Logo wrapper */}
          <View style={styles.logoWrapper}>
            {/* Logo */}
            <Animatable.Image
              source={require('../../assets/images/logo.png')}
              style={styles.logo}
              delay={600}
              animation="fadeInDown"
              easing="ease-in-out-back"
              useNativeDriver={true}
            />
          </View>
        </View>
      </View>
    </Animatable.View>
  );
};

// Exporting
export default Splash;
