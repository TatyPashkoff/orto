<?php

namespace backend\models;

use Yii;

class UserInfo extends \yii\db\ActiveRecord
{
    public $user_type1;
    public $user_type2;
    public $user_type3;

    public $partnerY = 'Y';
    public $partnerN;

    public $prY = 'Y';
    public $prN;

    public $initialPreviewImg = [];
    public $initialPreviewImgMob = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //////////////////// Пользователи
            [['user_type', 'fullname'], 'required'],
            [['user_type1', 'user_type2', 'user_type3'], 'filter', 'filter' => 'trim'],
            [['user_type1', 'user_type2', 'user_type3'], 'in', 'range' => ['0', '1', '2', '3']],
            [['active'], 'in', 'range' => ['0', '1']],

            ['fullname', 'filter', 'filter' => 'trim'],
            ['fullname', 'unique'],
            ['fullname', 'string', 'min' => 3, 'max' => 255],


            ['second_name', 'filter', 'filter' => 'trim'],
            ['second_name', 'string', 'min' => 3, 'max' => 255],

            [['avatar', 'avatar_mob'], 'filter', 'filter' => 'trim'],
            [['avatar', 'avatar_mob'], 'string', 'min' => 3, 'max' => 255],

            /////////////////////// Имиджмейкеры


            ['website', 'filter', 'filter' => 'trim'],
            ['website', 'url', 'defaultScheme' => 'http', 'enableIDN' => true],

            [['city'], 'filter', 'filter' => 'trim'],

            [['description'], 'filter', 'filter' => 'trim'],
            [['description'], 'string', 'min' => 3, 'max' => 255],

            [['initialPreviewImg'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],


            [['category1', 'category2', 'category3', 'category4', 'category5', 'category6', 'category7', 'category8', 'category9', 'category10', 'category11', 'category12', 'category13', 'category14', 'category15', 'category16', 'category17', 'category18', 'category19', 'category20', 'category21', 'category22', 'category23', 'category24', 'pr1', 'pr2', 'pr3', 'pr4', 'social1', 'social2', 'social3', 'social4', 'social5', 'social6', 'sort'], 'filter', 'filter' => 'trim'],
            [['category1', 'category2', 'category3', 'category4', 'category5', 'category6', 'category7', 'category8', 'category9', 'category10', 'category11', 'category12', 'category13', 'category14', 'category15', 'category16', 'category17', 'category18', 'category19', 'category20', 'category21', 'category22', 'category23', 'category24', 'category25', 'category26', 'category27', 'category28', 'category29', 'category30', 'pr1', 'pr2', 'pr3', 'pr4', 'social1', 'social2', 'social3', 'social4', 'social5', 'social6'], 'in', 'range' => ['0', '1']],

            [['socialUrl1', 'socialUrl2', 'socialUrl3', 'socialUrl4', 'socialUrl5', 'socialUrl6'], 'filter', 'filter' => 'trim'],
            [['socialUrl1', 'socialUrl2', 'socialUrl3', 'socialUrl4', 'socialUrl5', 'socialUrl6'], 'url', 'defaultScheme' => 'http'],

            [['partner', 'partnerY', 'partnerN', 'pr', 'prY', 'prN'], 'filter', 'filter' => 'trim'],
            [['partner', 'partnerY', 'partnerN', 'pr', 'prY', 'prN'], 'in', 'range' => ['0', 'Y', 'N']],

            [['contactPerson'], 'filter', 'filter' => 'trim'],
            [['contactPerson'], 'string', 'min' => 3, 'max' => 255],

            [['contactEmail'], 'email'],  // проверка email
         //   [['contactEmail'], 'filter', 'filter' => 'trim'],
         //   [['contactEmail'], 'string', 'min' => 7, 'max' => 255],

            [['phone'], 'string', 'message'=>'Длина номера телефона не должна быть меньше 7 знаков', 'min' => 7, 'max' => 24],
            [['phone'], 'match', 'message'=>'Укажите верный номер телефона', 'pattern' => '/^\+?((8|7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,12}$/i'],

            /////////////////////////// Бренд/Магазин

            ['prDescr', 'filter', 'filter' => 'trim'],
            ['prDescr', 'string', 'min' => 3, 'max' => 255],
            ['sort', 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'id' => 'ID',
            'avatar' => 'Лого (160x160)',
            'user_type1' => 'Пользователь',
            'user_type2' => 'Имиджмейкер',
            'user_type3' => 'Бренд',
            'active' => 'Активность',
            'first_name' => 'Название/Имя',
            'second_name' => 'Фамилия',

            'website' => 'САЙТ',
            'city' => 'ГОРОД',
            'description' => 'Описание',
            'category1' => 'Имидж-консалтинг',
            'category2' => 'Разбор гардероба',
            'category3' => 'Шопинг в городе',
            'category4' => 'Шопинг в Европе',
            'category5' => 'Шопинг в Америке',
            'category6' => 'Мужской стиль',
            'category7' => 'Online-консалтинг',
            'category8' => 'Обучение',
            'category9' => 'Мастер-классы',
            'category10' => 'Стилизация съемок',
            'category11' => 'Подарочные сертификаты',

            'pr1' => 'Консультирование в рамках форума',
            'pr2' => 'Размещение анонсов',
            'pr3' => 'Участие в конкурсах',
            'pr4' => 'Участие в фотосессиях',

            'social1' => 'Facebook',
            'social2' => 'VK.com',
            'social3' => 'Instagram',
            'social4' => 'Google+',
            'social5' => 'Twitter',
            'social6' => 'LinkedIn',
            'socialUrl1' => 'Укажите ссылку',
            'socialUrl2' => 'Укажите ссылку',
            'socialUrl3' => 'Укажите ссылку',
            'socialUrl4' => 'Укажите ссылку',
            'socialUrl5' => 'Укажите ссылку',
            'socialUrl6' => 'Укажите ссылку',


            'partner' => 'Участие в партнерской программе',
            'partnerY' => 'Да',
            'partnerN' => 'Нет',



            'contactPerson' => 'КОНТАКТНОЕ ЛИЦО',
            'contactEmail' => 'Укажите Е-mail',
            'phone' => 'Введите номер телефона',

            'type1' => 'Модная марка',
            'type2' => 'Интернет-магазин',
            'type3' => 'ТРЦ',
            'type4' => 'Туристическая организация',
            'type5' => 'Отель',
            'type6' => 'Кинотеатр, ресторан, выставка',
            'type7' => 'Салон/институт красоты',

            'category12' => 'одежда',
            'category13' => 'сумки/обувь',
            'category14' => 'украшения',
            'category15' => 'прочие аксессуары',
            'category16' => 'одежда',
            'category17' => 'сумки/обувь',
            'category18' => 'прочие аксессуары',
            'category19' => 'одежда',
            'category20' => 'игрушки',
            'category21' => 'Индивидуальный пошив',
            'category22' => 'Красота',
            'category23' => 'LIFE STYLE',
            'category24' => 'Декор интерьера',
            'category25' => 'отечественные дизайнеры',
            'category26' => 'размер +',
            'category27' => 'вторая жизнь',
            'category28' => 'отечественные дизайнеры',
            'category29' => 'размер +',
            'category30' => 'вторая жизнь',
            'avatar_mob' => ' Лого (160x160) аватар для мобилный',
            'pr' => 'PR-активность',
            'prY' => 'Да',
            'prN' => 'Нет',
            'prDescr' => 'Описание/концепция бренда в одном предложении (не обязательно)',
            'sort'=> 'Сортировка',
            
            'country' =>'Страна', // 26072016  страна также нужно в profile-info 
        ];
    }



    public function scenarios(){
        $scenarios = parent::scenarios();
       // $scenarios['default'] = ['id', 'user_type', 'active', 'first_name', 'second_name', 'avatar', 'avatar_mob' , 'website', 'city', 'description', 'category1', 'category2', 'category3', 'category4', 'category5', 'category6', 'category7', 'category8', 'category9', 'category10', 'category11', 'category12', 'category13', 'category14', 'category15', 'category16', 'category17', 'category18', 'category19', 'category20', 'category21', 'category22', 'category23', 'category24', 'category25', 'category26', 'category27', 'category28', 'category29', 'category30', 'pr', 'pr1', 'pr2', 'pr3', 'pr4', 'social1', 'social2', 'social3', 'social4', 'social5', 'social6', 'socialUrl1', 'socialUrl2', 'socialUrl3', 'socialUrl4', 'socialUrl5', 'socialUrl6', 'type1', 'type2', 'type3', 'type4', 'type5', 'type6', 'type7', 'prDescr', 'contactPerson', 'contactEmail', 'sort', '!user_type1', '!user_type2', '!user_type3', 'partner', '!partnerY', '!partnerN', '!prY', '!prN', '!initialPreviewImg'];
        $scenarios['default'] = ['id', 'user_type', 'active', 'fullname', 'second_name', 'avatar', 'avatar_mob' , 'website', 'city', 'description', 'category1', 'category2', 'category3', 'category4', 'category5', 'category6', 'category7', 'category8', 'category9', 'category10', 'category11', 'category12', 'category13', 'category14', 'category15', 'category16', 'category17', 'category18', 'category19', 'category20', 'category21', 'category22', 'category23', 'category24', 'category25', 'category26', 'category27', 'category28', 'category29', 'category30', 'pr', 'pr1', 'pr2', 'pr3', 'pr4', 'social1', 'social2', 'social3', 'social4', 'social5', 'social6', 'socialUrl1', 'socialUrl2', 'socialUrl3', 'socialUrl4', 'socialUrl5', 'socialUrl6', 'type1', 'type2', 'type3', 'type4', 'type5', 'type6', 'type7', 'prDescr', 'contactPerson', 'contactEmail','phone', 'sort', 'country', '!user_type1', '!user_type2', '!user_type3', 'partner', '!partnerY', '!partnerN', '!prY', '!prN', '!initialPreviewImg'];
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getPosts()
    // {
    //     return $this->hasMany(Posts::className(), ['user_info_id' => 'id']);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserType()
    {
        return $this->hasOne(UserType::className(), ['id' => 'user_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressUserInfos()
    {
        return $this->hasMany(AddressUserInfo::className(), ['profile_id' => 'id']);
    }


    /**
     * @inheritdoc
     * @return UserInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserInfoQuery(get_called_class());
    }


}
