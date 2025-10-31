//import liraries
import { NavigationContainer } from '@react-navigation/native';
import React, { useEffect, useState } from 'react';
import { GestureHandlerRootView } from 'react-native-gesture-handler';
import AppStyles from './AppStyles';
import Routes from './src/navigators/Routes';
import Splash from './src/screens/Splash';
import FlashMessage from 'react-native-flash-message';
import { Provider } from 'react-redux';
import store, { persistor } from './src/redux/store/Store';
import { PersistGate } from 'redux-persist/integration/react';

// create a component
const App = () => {
  // Local states
  const [isStarting, setIsStarting] = useState(true);

  // Hooks
  useEffect(() => {
    // Updating state value after 3500 milliseconds(3.5 seconds) of delay. You may change the value of milliseconds(3500) as per your need.
    setTimeout(() => {
      // Updating states
      setIsStarting(false);
    }, 3500);
  }, []);

  // Checking
  if (isStarting) {
    // Returning
    return <Splash />;
  }

  return (
    <GestureHandlerRootView style={AppStyles.gestureHandlerRootView}>
      <Provider store={store}>
      <PersistGate loading={null} persistor={persistor}>
        <NavigationContainer>
          <Routes />
          <FlashMessage position="top" />
        </NavigationContainer>
        </PersistGate>
      </Provider>

    </GestureHandlerRootView>
  );
};

export default App;
