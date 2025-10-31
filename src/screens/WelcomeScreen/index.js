import React, { useRef, useState } from 'react';
import { Animated, Image, Pressable, TouchableOpacity, useWindowDimensions, View } from 'react-native';
import { CustomText } from '../../components/global/CustomComponents.js';
import styles from './styles';
import { Colors, LightThemeColors } from '../../config/Colors.js';
import CustomSafeAreaView from "../../components/global/CustomSafeAreaView";
import NextIcon from '../../assets/icons/svg/New/Frame 4.svg';
import { SCREEN_WIDTH, STANDARD_VECTOR_ICON_SIZE } from '../../config/Constants.js';
import { useNavigation } from '@react-navigation/native';
import { useDispatch } from 'react-redux';
import { welcomeUser } from '../../redux/slices/SessionUser.js';

const OnboardingData = [
    {
        id: '1',
        image: require('../../assets/images/4565.png'),
        title: 'Welcome to N.K. Edibles',
        subTitle: 'Your digital companion for the sweetest workplace',
        description: 'Managing your work schedule and attendance has never been easier.'
    },
    {
        id: '2',
        image: require('../../assets/images/21404.png'),
        title: 'Track Your Shifts',
        subTitle: 'Stay on top of your work schedule',
        description: 'View upcoming shifts, request time off, and manage your work calendar with ease.'
    },
    {
        id: '3',
        image: require('../../assets/images/19283.png'),
        title: 'Clock In & Out',
        subTitle: 'Seamless attendance tracking',
        description: "Use biometric authentication to clock in and out securely from your mobile device."
    },
];

const OnbordingItem = ({ item }) => {
    return (
        <View style={{ width: SCREEN_WIDTH * 1 }}>
            <View style={styles.imageWrapper}>
                <Image style={styles.image} source={item.image} />
            </View>
            <View style={styles.card}>
                <CustomText style={[styles.title, { color: LightThemeColors.titleColor }]}>
                    {item.title}
                </CustomText>
                <CustomText style={[styles.subTitle, { color: LightThemeColors.textLowContrast }]}>
                    {item.subTitle}
                </CustomText>
                <CustomText style={[styles.description, { color: LightThemeColors.textLowContrast }]}>
                    {item.description}
                </CustomText>
            </View>
        </View>
    );
};

const WelcomeScreen = () => {
    const [currentIndex, setCurrentIndex] = useState(0);
    const OnbordingdataRef = useRef(null);
    const navigation = useNavigation();
    const ScrollX = useRef(new Animated.Value(0)).current;
    const width = useWindowDimensions().width;

    const dispatch = useDispatch();
  

    const handleWelcome = () => {
      dispatch(welcomeUser(true)); 
      console.log('welcomeFlag set to true');
    };

    const ViewableItemsChanged = useRef(({ viewableItems }) => {
        if (viewableItems && viewableItems.length > 0) {
            setCurrentIndex(viewableItems[0].index);
        }
    }).current;

    const ViewConfig = useRef({ itemVisiblePercentThreshold: 50 }).current;

    const ScrollTo = () => {
        if (currentIndex < OnboardingData.length - 1) {
            OnbordingdataRef.current.scrollToIndex({ index: currentIndex + 1 });
        } else {
            navigation.navigate('LogInScreen'); // Next screen
            handleWelcome()
        }
    };

    return (
        <CustomSafeAreaView>
            <View style={[styles.container, { backgroundColor: Colors.white }]}>
                <View style={styles.header}>
                    <TouchableOpacity onPress={() =>{ handleWelcome()
                        ,navigation.navigate('LogInScreen')}}>
                        <CustomText style={[styles.buttonText, { color: LightThemeColors.textLowContrast }]}>
                            Skip
                        </CustomText>
                    </TouchableOpacity>
                </View>

                <Animated.FlatList
                    // scrollEnabled={false}

                    horizontal
                    showsHorizontalScrollIndicator={false}
                    data={OnboardingData}
                    renderItem={({ item }) => <OnbordingItem item={item} />}
                    pagingEnabled
                    bounces={false}
                    keyExtractor={(item) => item.id}
                    onScroll={Animated.event(
                        [{ nativeEvent: { contentOffset: { x: ScrollX } } }],
                        { useNativeDriver: false }
                    )}
                    scrollEventThrottle={32}
                    onViewableItemsChanged={ViewableItemsChanged}
                    viewabilityConfig={ViewConfig}
                    ref={OnbordingdataRef}
                />

                <View style={styles.bottomView}>
                    <View style={styles.indicatorView}>
                        {OnboardingData.map((_, i) => {
                            const inputRange = [(i - 1) * width, i * width, (i + 1) * width];

                            const dotWidth = ScrollX.interpolate({
                                inputRange,
                                outputRange: [20, 80, 20],
                                extrapolate: 'clamp',
                            });

                            const opacity = ScrollX.interpolate({
                                inputRange,
                                outputRange: [0.2, 1, 0.3],
                                extrapolate: 'clamp',
                            });

                            return (
                                <Animated.View
                                    style={[styles.dot, { width: dotWidth, opacity }]}
                                    key={i.toString()}
                                />
                            );
                        })}</View>
                    <Pressable onPress={ScrollTo}>
                        <NextIcon
                            height={STANDARD_VECTOR_ICON_SIZE * 2.6}
                            width={STANDARD_VECTOR_ICON_SIZE * 2.6}
                        />
                    </Pressable>
                </View>
            </View>
        </CustomSafeAreaView>
    );
};

export default WelcomeScreen;
