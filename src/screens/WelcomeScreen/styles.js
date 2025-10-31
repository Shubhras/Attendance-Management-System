import { StyleSheet } from "react-native";
import { FONT_SIZE_LG, FONT_SIZE_SM, FONT_SIZE_XS, POPPINS_BOLD, POPPINS_MEDIUM, POPPINS_REGULAR, POPPINS_SEMIBOLD, SCREEN_HEIGHT, SCREEN_WIDTH, STANDARD_BORDER_RADIUS, STANDARD_FLEX, STANDARD_SPACING } from "../../config/Constants";
import { scale } from "react-native-size-matters";
import { Colors, LightThemeColors } from "../../config/Colors";

export default StyleSheet.create({
    container: {
        flex: STANDARD_FLEX
    },
    header: {
        alignItems: 'flex-end',
        paddingHorizontal: SCREEN_WIDTH * 0.05,
        marginTop: STANDARD_SPACING * 4
    },
    buttonText: {
        fontFamily: POPPINS_REGULAR,
        fontSize: FONT_SIZE_SM
    },

    imageWrapper: {
        paddingHorizontal: SCREEN_WIDTH * 0.05,
        alignItems: 'center',
        marginTop: STANDARD_SPACING * 6,
    },
    image: {
        width: '100%',
        aspectRatio: 4 / 3,
        resizeMode: 'contain',
    },
    card: {
        height: SCREEN_HEIGHT * 0.5,
        width: SCREEN_WIDTH * 1,
        bottom: 0,
        position: 'absolute',
        borderTopLeftRadius: STANDARD_BORDER_RADIUS * 6,
        borderTopRightRadius: STANDARD_BORDER_RADIUS * 6,

        shadowColor: '#B7B89F',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.1,
        shadowRadius: STANDARD_BORDER_RADIUS * 7,
        elevation: 2.8,
        paddingHorizontal: SCREEN_WIDTH * 0.05,
        paddingTop: STANDARD_SPACING * 10,
    },
    title: {
        fontFamily: POPPINS_SEMIBOLD,
        fontSize: FONT_SIZE_LG
    },
    subTitle: {
        fontFamily: POPPINS_REGULAR,
        fontSize: FONT_SIZE_SM,
        marginTop: STANDARD_SPACING * 2,
        lineHeight: scale(28)

    },
    description: {
        fontFamily: POPPINS_MEDIUM,
        fontSize: FONT_SIZE_SM,
        marginTop: STANDARD_SPACING * 6,
        lineHeight: scale(28)
    },
    bottomView: {
        width: '100%',
        justifyContent: 'space-between',
        flexDirection: 'row',
        marginTop: STANDARD_SPACING * 10,
        paddingHorizontal: SCREEN_WIDTH * 0.05,
        position: 'absolute',
        bottom: scale(10),
        alignSelf: 'center',

    },

    nextButtonView: {
        borderRadius: scale(20),
        height: scale(40),
        width: scale(40),
        alignItems: 'center',
        justifyContent: 'center'
    },
    indicatorView: {
        width: SCREEN_WIDTH * 0.6,
        flexDirection: 'row',
        alignItems: 'center',
        columnGap: scale(5)
    },
    dot: {
        height: 10,
         borderRadius: 5,
        backgroundColor: Colors.pomegranate,
        marginHorizontal: 0,
      },
});