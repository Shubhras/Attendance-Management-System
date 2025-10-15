//import liraries
import React, { Component } from 'react';
import { View, Text, StyleSheet } from 'react-native';

const WelcomeScreen = () => {
    return (
        <View style={styles.container}>
            <Text>WelcomeScreen</Text>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#fff',
    },
});

export default WelcomeScreen;
