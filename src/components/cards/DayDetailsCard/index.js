import { Image, Pressable, View } from 'react-native';
import styles from './styles';
import { CustomText } from '../../global/CustomComponents';
import { LightThemeColors } from '../../../config/Colors';
import Icons from '../../Icons/Icons';
import moment from 'moment';
import { scale } from 'react-native-size-matters';
const DayDetailsCard = ({
  status = '0',
  time_in,
  time_out,
  date,
  day,
  onPress,
  slots = [],
}) => {
  console.log('statusstatusstatusstatus', slots);

  const getStatusStyle = status => {
    switch (status?.toLowerCase()) {
      case '1':
        return {
          color: '#4CAF50',
          icon: 'checkmark',
          iconType: 'Ionicons',
          name: 'Present',
        };

      case '0':
        return {
          color: '#F44336',
          icon: 'close-outline',
          iconType: 'Ionicons',
          name: 'Absent',
        };

      case '2':
        return {
          color: '#FFC107',
          icon: 'exclamation',
          iconType: 'SimpleLineIcons',
          name: 'Half-day',
        };

      case 'pending':
        return {
          color: '#CBD5E1',
          icon: 'exclamation',
          iconType: 'SimpleLineIcons',
          name: 'Present',
        };

      default:
        return;
    }
  };
  const getDayOfMonth = dateString => {
    const date = new Date(dateString);
    return date.getDate(); // returns 1 for "2025-10-01"
  };
  return (
    <View
      style={[
        styles.cardWrapper,
        { borderLeftColor: getStatusStyle(status).color },
      ]}
    >
      <Pressable style={styles.card} onPress={onPress}>
        <View style={styles.textView}>
          {date && day && (
            <CustomText
              style={[styles.id, { color: LightThemeColors.textLowContrast }]}
            >
              {getDayOfMonth(date)} {day}
            </CustomText>
          )}
          {time_in && (
            // && time_out
            <CustomText
              style={[
                styles.subtitle,
                { color: LightThemeColors.textLowContrast },
              ]}
            >
              {time_in}
              {/* - {time_out} */}
            </CustomText>
          )}
          {slots && (
            // && time_out
            <View style={{ flexDirection: 'column', gap: 0 }}>
              {slots?.map((val, i) => {
                return (
                  <>
                    {val != null && (
                      <CustomText
                        key={i + 'slot'}
                        style={[
                          styles.subtitle,
                          { color: LightThemeColors.textLowContrast },
                        ]}
                      >
                        {i == 0 ? 'S1=>' : i == 1 ? 'S2=>' : 'S3=>'}{' '}
                        {moment(val).format('hh:mm A')}
                      </CustomText>
                    )}
                  </>
                );
              })}
            </View>
          )}
        </View>
        <View style={[styles.statusButton, { borderColor:  getStatusStyle(status).color}]}>
          <Icons
            name={getStatusStyle(status)?.icon}
            iconType={getStatusStyle(status)?.iconType}
            color={getStatusStyle(status)?.color}
            size={scale(16)}
          />
          <CustomText style={styles.statusText}>
            {getStatusStyle(status)?.name}
          </CustomText>
        </View>
      </Pressable>
    </View>
  );
};
export default DayDetailsCard;
