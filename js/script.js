

var cities = [
  {
      cityName: 'город Екатеринбург',
      offices: [
          {
              coordinates: [56.829374, 60.672699],
              city: 'Екатеринбург, Свердловская область, Россия', 
              name: 'Сибирский тракт (дублёр), 2',
              phone: '8 (343) 273-00-08',
              balloon: {
                      balloonContentHeader: 'город Екатеринбург',
                      balloonContentBody: 'Екатеринбург, Свердловская область, Россия',
                      hintContent: 'Сибирский тракт (дублёр), 2'
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
          {
              coordinates: [56.798048, 60.560114],
              city: '620146, г. Екатеринбург, ул. Громова, д. 145', 
              phone: '',
              balloon: {
                  balloonContentHeader: '',
                  balloonContentBody: '',
                  hintContent: ''
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
          {
              'coordinates': [56.781996, 60.520767],
              'city': '620105, Свердловская область, г. Екатеринбург, пр-кт Академика Сахарова, д. 45', 
              "phone": '',
              balloon: {
                  balloonContentHeader: 'г. Екатеринбург, ',
                  balloonContentBody: '620105, Свердловская область, г. Екатеринбург, пр-кт Академика Сахарова, д. 45',
                  hintContent: 'пр-кт Академика Сахарова, д. 45'
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
          {
              'coordinates': [56.836357, 60.600322],
              'city': '620014, г. Екатеринбург, ул. 8 Марта, д. 13', 
              "phone": '',
              balloon: {
                  balloonContentHeader: 'г. Екатеринбург,',
                  balloonContentBody: '620014, г. Екатеринбург, ул. 8 Марта, д. 13',
                  hintContent: ' ул. 8 Марта, д. 13'
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
          {
              'coordinates': [56.782882, 60.664773],
              'city': '620023, г. Екатеринбург, ул. Рощинская, д. 21', 
              "phone": '',
              balloon: {
                  balloonContentHeader: 'г. Екатеринбург,',
                  balloonContentBody: '620023, г. Екатеринбург, ул. Рощинская, д. 21',
                  hintContent: ' ул. Рощинская, д. 21'
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
          {
              coordinates: [56.856717, 60.597978],
              city: '620107, г. Екатеринбург, ул. Героев России, 2', 
              phone: '',
              balloon: {
                  balloonContentHeader: 'г. Екатеринбург ',
                  balloonContentBody: '620107, г. Екатеринбург, ул. Героев России, 2',
                  hintContent: 'ул. Героев России, 2'
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
          {
              coordinates: [56.895845, 60.608785],
              city: '620012, г. Екатеринбург, ул. Победы, 14А', 
              phone: '',
              balloon: {
                  balloonContentHeader: 'г. Екатеринбург',
                  balloonContentBody: '620012, г. Екатеринбург, ул. Победы, 14А',
                  hintContent: 'ул. Победы, 14А'
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
          {
              coordinates: [56.888505, 60.616375],
              city: '620017, г. Екатеринбург, ул. Баумана, д. 5', 
              phone: '',
              balloon: {
                  balloonContentHeader: 'г. Екатеринбург',
                  balloonContentBody: '620017, г. Екатеринбург, ул. Баумана, д. 5',
                  hintContent: ' ул. Баумана, д. 5'
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
          {
              coordinates: [56.858464, 60.632024],
              city: '620137, г. Екатеринбург, ул. Учителей, д. 2Б', 
              phone: '',
              balloon: {
                  balloonContentHeader: 'г. Екатеринбург ',
                  balloonContentBody: '620137, г. Екатеринбург, ул. Учителей, д. 2Б',
                  hintContent: 'ул. Учителей, д. 2Б'
              },
              icon: {
                  iconLayout: 'default#image',
                  iconImageHref: '../img/geo_red.png',
                  iconImageSize: [38, 48],
                  iconImageOffset: [-15, -62]
              }
          },
      ]
  },
  {
    'cityName': 'Артемовский район',
    'offices': [
        {
            coordinates: [57.339111, 61.895980],
            city: '623785, Свердловская область, г. Артемовский, ул. Почтовая, д. 2',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Артемовский,',
                balloonContentBody: '623785, Свердловская область, г. Артемовский, ул. Почтовая, д. 2',
                hintContent: ' ул. Почтовая, д. 2'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        
    ]
  },
  {
    'cityName': 'Артинский район',
    'offices': [
        {
            coordinates: [56.414802, 58.532867],
            city: '623340, Свердловская область, пгт. Арти, ул. Рабочей Молодежи, д. 113А',
            phone: '8 (343) 273-00-08',
            balloon: {
                balloonContentHeader: ' Свердловская область, пгт. Арти, ',
                balloonContentBody: '623340, Свердловская область, пгт. Арти, ул. Рабочей Молодежи, д. 113А',
                hintContent: 'ул. Рабочей Молодежи, д. 113А'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        
    ]
  },
  {
    'cityName': 'Байкаловский район',
    'offices': [
        {
            coordinates: [57.535714, 63.647938],
            city: '623881, Свердловская область, Байкаловский р-н, с. Краснополянское, ул. Советская, д. 26',
            phone: '0',
            balloon: {
                balloonContentHeader: 'Свердловская область, Байкаловский р-н ',
                balloonContentBody: '623881, Свердловская область, Байкаловский р-н, с. Краснополянское, ул. Советская, д. 26',
                hintContent: 'с. Краснополянское, ул. Советская, д. 26'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        {
            coordinates: [57.397572, 63.771043],
            city: '623870, Свердловская область, с. Байкалово, ул. Революции, д. 25',
            phone: '0',
            balloon: {
                balloonContentHeader: 'Свердловская область, с. Байкалово, ',
                balloonContentBody: '623870, Свердловская область, с. Байкалово, ул. Революции, д. 25',
                hintContent: 'ул. Революции, д. 25'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        
    ]
  },
  {
    'cityName': 'Белоярский район',
    'offices': [
        {
            coordinates: [56.759715, 61.415094],
            city: '624030, Свердловская область, пгт. Белоярский, ул. Милицейская, д. 3',
            phone: '',
            balloon: {
                balloonContentHeader: 'Белоярский район',
                balloonContentBody: '624030, Свердловская область, пгт. Белоярский, ул. Милицейская, д. 3',
                hintContent: 'пгт. Белоярский, ул. Милицейская, д3. '
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        
    ]
  },
  {
    'cityName': 'город Березовский',
    'offices': [
        {
            coordinates: [56.896544, 60.741942],
            city: '623704, Свердловская область, г. Березовский, ул. Героев Труда, д. 23',
            phone: '',
            balloon: {
                balloonContentHeader: 'город Березовский',
                balloonContentBody: '623704, Свердловская область, г. Березовский, ул. Героев Труда, д. 23',
                hintContent: 'г. Березовский, ул. Героев Труда, д. 23'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        
    ]
  },
  {
    'cityName': 'город Асбест',
    'offices': [
        {
            coordinates: [57.009068, 61.466648],
            city: '624261, Свердловская область, г. Асбест, ул. Уральская, д. 63',
            phone: '',
            balloon: {
                balloonContentHeader: 'город Асбест',
                balloonContentBody: '624261, Свердловская область, г. Асбест, ул. Уральская, д. 63',
                hintContent: 'г. Асбест, ул. Уральская, д. 63'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        {
            coordinates: [57.002377, 61.454629],
            city: '624262, Свердловская область, г. Асбест, ул. Чапаева, д. 39',
            phone: '8 (343) 273-00-08',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Асбест,',
                balloonContentBody: '624262, Свердловская область, г. Асбест, ул. Чапаева, д. 39',
                hintContent: ' ул. Чапаева, д. 39'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': 'Ачитский район',
    'offices': [
        {
            coordinates: [56.798556, 57.896510],
            city: '623230, Свердловская область, пгт. Ачит, ул. Кривозубова, д. 8',
            phone: '',
            balloon: {
                balloonContentHeader: 'Ачитский район',
                balloonContentBody: '623230, Свердловская область, пгт. Ачит, ул. Кривозубова, д. 8',
                hintContent: 'ул. Кривозубова, д. 8'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': 'Верхнесалдинский район',
    'offices': [
        {
            coordinates: [58.047468, 60.544141],
            city: '624760, Свердловская область, г. Верхняя Салда, ул. Карла Маркса, д. 3',
            phone: '',
            balloon: {
                balloonContentHeader: 'Верхнесалдинский район',
                balloonContentBody: '624760, Свердловская область, г. Верхняя Салда, ул. Карла Маркса, д. 3',
                hintContent: 'ул. Карла Маркса, д. 3'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        
    ]
  },
  {
    'cityName': 'Гаринский район',
    'offices': [
        {
            coordinates: [59.427064, 62.352603],
            city: '624910, Свердловская область, пгт. Гари, ул. Комсомольская, д. 52',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, пгт. Гари',
                balloonContentBody: '624910, Свердловская область, пгт. Гари, ул. Комсомольская, д. 52',
                hintContent: 'ул. Комсомольская, д. 52'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': 'Богдановичский район',
    'offices': [
        {
            coordinates: [56.773193, 62.051784],
            city: '623530, Свердловская область, г. Богданович, ул. Партизанская, д. 9',
            phone: '8 (343) ',
            balloon: {
                balloonContentHeader: '623530, Свердловская область, Богданович,',
                balloonContentBody: '623530, Свердловская область, г. Богданович, ул. Партизанскаг.',
                hintContent: 'ул. Партизанская, д. 9я, д. 9'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': 'город Верхняя Пышма',
    'offices': [
        {
            coordinates: [56.976809, 60.556709],
            city: '624090, Свердловская область, г. Верхняя Пышма, ул. Юбилейная, д. 20',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Верхняя Пышма, ',
                balloonContentBody: '624090, Свердловская область, г. Верхняя Пышма, ул. Юбилейная, д. 20',
                hintContent: 'ул. Юбилейная, д. 20'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        {
            coordinates: [56.963700, 60.612485],
            city: '624092, Свердловская область, г. Верхняя Пышма, ул. Победы, д. 11',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Верхняя Пышма,',
                balloonContentBody: '624092, Свердловская область, г. Верхняя Пышма, ул. Победы, д. 11',
                hintContent: 'ул. Победы, д. 11'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': 'Верхотурский район',
    'offices': [
        {
            coordinates: [58.860513, 60.811534],
            city: '624380, Свердловская область, г. Верхотурье, ул. Карла Маркса, д. 2',
            phone: '8 (343) 273-00-08',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Верхотурье',
                balloonContentBody: '624380, Свердловская область, г. Верхотурье, ул. Карла Маркса, д. 2',
                hintContent: 'ул. Карла Маркса, д. 2'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        {
            coordinates: [58.879281, 60.727515],
            city: '624390, Свердловская область, п. Привокзальный, ул. Советская, д 6А',
            phone: '8 (343) 273-00-08',
            balloon: {
                balloonContentHeader: 'Свердловская область, п. Привокзальный, ',
                balloonContentBody: '624390, Свердловская область, п. Привокзальный, ул. Советская, д 6А',
                hintContent: 'ул. Советская, д 6А'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
      ]
  },
  {
    'cityName': 'город Нижняя Тура',
    'offices': [
        {
            coordinates: [57.915872, 60.133036],
            city: '622052, Свердловская область, г. Нижний Тагил, пр-кт. Вагоностроителей, д. 64',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Нижний Тагил, ',
                balloonContentBody: '622052, Свердловская область, г. Нижний Тагил, пр-кт. Вагоностроителей, д. 64',
                hintContent: 'пр-кт. Вагоностроителей, д. 64'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        {
            coordinates: [57.923267, 59.934958],
            city: '622002, Свердловская область, г. Нижний Тагил, ул. Космонавтов, д. 45',
            phone: '8 (343) 273-00-08',
            balloon: {
                balloonContentHeader: ' Свердловская область, г. Нижний Тагил, ',
                balloonContentBody: '622002, Свердловская область, г. Нижний Тагил, ул. Космонавтов, д. 45',
                hintContent: 'ул. Космонавтов, д. 45'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        {
            coordinates: [57.940765, 60.000894],
            city: '622005, Свердловская область, г. Нижний Тагил, ул. Металлургов, д. 46Б',
            phone: '8 (343) 273-00-08',
            balloon: {
                balloonContentHeader: '622005, Свердловская область, г. Нижний Тагил, ',
                balloonContentBody: '622005, Свердловская область, г. Нижний Тагил, ул. Металлургов, д. 46Б',
                hintContent: 'ул. Металлургов, д. 46Б'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        
    ]
  },
  {
    'cityName': 'город Заречный',
    'offices': [
        {
            coordinates: [56.802731, 61.312902],
            city: '624250, Свердловская область, г. Заречный, ул. Курчатова, д. 23',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Заречный,',
                balloonContentBody: '624250, Свердловская область, г. Заречный, ул. Курчатова, д. 23',
                hintContent: 'ул. Курчатова, д. 23'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': 'Ирбитский район',
    'offices': [
        {
            coordinates: [57.560539, 62.760860],
            city: '623847, Свердловская область, п. Зайково, ул. Коммунистическая, д. 181',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, п. Зайково, ',
                balloonContentBody: '623847, Свердловская область, п. Зайково, ул. Коммунистическая, д. 181',
                hintContent: 'ул. Коммунистическая, д. 181'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        {
            coordinates: [57.881310, 62.758453],
            city: '623834, Свердловская область, Ирбитский р-н, с. Ницинское, ул. Центральная, д. 60',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, Ирбитский р-н, с. Ницинское, ',
                balloonContentBody: '623834, Свердловская область, Ирбитский р-н, с. Ницинское, ул. Центральная, д. 60',
                hintContent: 'ул. Центральная, д. 60'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        
    ]
  },
  {
    'cityName': 'город Карпинск',
    'offices': [
        {
            coordinates: [59.769732, 59.994040],
            city: '624930, Свердловская область, г. Карпинск, ул. Луначарского, д. 88',
            phone: '8 (343) 273-00-08',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Карпинск, ',
                balloonContentBody: '624930, Свердловская область, г. Карпинск, ул. Луначарского, д. 88',
                hintContent: 'ул. Луначарского, д. 88'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': 'город Краснотурьинск',
    'offices': [
        {
            coordinates: [59.763597, 60.181554],
            city: '624447, Свердловская область, г. Краснотурьинск, ул. Ленина, д. 4',
            phone: '',
            balloon: {
                balloonContentHeader: 'Свердловская область, г. Краснотурьинск, ',
                balloonContentBody: '624447, Свердловская область, г. Краснотурьинск, ул. Ленина, д. 4',
                hintContent: 'ул. Ленина, д. 4'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
        {
            coordinates: [59.698699, 60.292946],
            city: '624465, Свердловская область, п.Рудничный, ул. Первомайская, д. 2',
            phone: '',
            balloon: {
                balloonContentHeader: ' Свердловская область, п.Рудничный,',
                balloonContentBody: '624465, Свердловская область, п.Рудничный, ул. Первомайская, д. 2',
                hintContent: ' ул. Первомайская, д. 2'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': 'город Красноуральск',
    'offices': [
        {
          coordinates: [58.356301, 60.034437],
          city: '624330, Свердловская область, г. Красноуральск, ул. Иллариона Янкина, д. 7',
          phone: '',
          balloon: {
              balloonContentHeader: 'Свердловская область, г. Красноуральск, ',
              balloonContentBody: '624330, Свердловская область, г. Красноуральск, ул. Иллариона Янкина, д. 7',
              hintContent: 'ул. Иллариона Янкина, д. 7'
          },
          icon: {
              iconLayout: 'default#image',
              iconImageHref: '../img/geo_red.png',
              iconImageSize: [38, 48],
              iconImageOffset: [-15, -62]
          }
        },
    ]
  },
  {
    'cityName': 'город Красноуфимск',
    'offices': [
        {
            coordinates: [56.612856, 57.779289],
            city: '623300, Свердловская область, г. Красноуфимск, ул. Манчажская, д. 15',
            phone: '',
            balloon: {
                balloonContentHeader: '623300, Свердловская область, г. Красноуфимск, ',
                balloonContentBody: '623300, Свердловская область, г. Красноуфимск, ул. Манчажская, д. 15',
                hintContent: 'ул. Манчажская, д. 15'
            },
            icon: {
                iconLayout: 'default#image',
                iconImageHref: '../img/geo_red.png',
                iconImageSize: [38, 48],
                iconImageOffset: [-15, -62]
            }
        },
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  {
    'cityName': '',
    'offices': [
        {
          'coordinates': [],
          'city': '',
          "phone": '8 (343) 273-00-08'
        },
        //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
    ]
  },
  

  

];


const dataLocation = [
    {
        coordinate: [57.852779, 61.703516],
        balloon: {
                balloonContentHeader: 'г. Алапаевск',
                balloonContentBody: 'г. Алапаевск, ул. Ленина, 16',
                hintContent: 'г. Алапаевск, ул. Ленина, 16'
        },
        icon: {
            iconLayout: 'default#image',
            iconImageHref: '../img/geo_red.png',
            iconImageSize: [38, 48],
            iconImageOffset: [-15, -62]
        }
    },
    {
        coordinate: [56.701512, 60.841187],
        balloon: {
                balloonContentHeader: 'г. Арамиль',
                balloonContentBody: 'г. Арамиль, ул. Щорса, 57',
                hintContent: 'г. Арамиль, ул. Щорса, 57'
        },
        icon: {
            iconLayout: 'default#image',
            iconImageHref: '../img/geo_red.png',
            iconImageSize: [38, 48],
            iconImageOffset: [-15, -62]
        }
    },
    {
        coordinate: [57.339111, 61.895989],
        balloon: {
                balloonContentHeader: 'г. Артемовский',
                balloonContentBody: 'г. Артемовский, ул. Почтовая, 2',
                hintContent: 'г. Артемовский, ул. Почтовая, 2'
        },
        icon: {
            iconLayout: 'default#image',
            iconImageHref: '../img/geo_red.png',
            iconImageSize: [38, 48],
            iconImageOffset: [-15, -62]
        }
    },
    {
        coordinate: [56.414802, 58.532867],
        balloon: {
                balloonContentHeader: 'пгт Арти',
                balloonContentBody: 'пгт Арти, ул. Рабочей молодежи, 113 а',
                hintContent: 'пгт Арти, ул. Рабочей молодежи, 113 а'
        },
        icon: {
            iconLayout: 'default#image',
            iconImageHref: '../img/geo_red.png',
            iconImageSize: [38, 48],
            iconImageOffset: [-15, -62]
        }
    },
    {
        coordinate: [57.009063, 61.466693],
        balloon: {
                balloonContentHeader: 'г. Асбест',
                balloonContentBody: 'г. Асбест, ул. Уральская, 63',
                hintContent: 'г. Асбест, ул. Уральская, 63'
        },
        icon: {
            iconLayout: 'default#image',
            iconImageHref: '../img/geo_red.png',
            iconImageSize: [38, 48],
            iconImageOffset: [-15, -62]
        }
    },
    {
        coordinate: [57.002373, 61.454674],
        balloon: {
                balloonContentHeader: 'г. Асбест',
                balloonContentBody: 'г. Асбест, ул. Чапаева, 39',
                hintContent: 'г. Асбест, ул. Чапаева, 39'
        },
        icon: {
            iconLayout: 'default#image',
            iconImageHref: '../img/geo_red.png',
            iconImageSize: [38, 48],
            iconImageOffset: [-15, -62]
        }
    },
    {
        coordinate: [56.693219, 59.421903],
        balloon: {
                balloonContentHeader: 'пгт Атиг, ул. Заводская, 8',
                balloonContentBody: 'пгт Атиг, ул. Заводская, 8',
                hintContent: 'пгт Атиг, ул. Заводская, 8'
        },
        icon: {
            iconLayout: 'default#image',
            iconImageHref: '../img/geo_grey.png',
            iconImageSize: [38, 48],
            iconImageOffset: [-15, -62]
        }
    },
    {
      coordinate: [56.798561, 57.896519],
      balloon: {
              balloonContentHeader: 'пгт Ачит',
              balloonContentBody: 'пгт Ачит, ул. Кривозубова, 8',
              hintContent: 'пгт Ачит, ул. Кривозубова, 8'
      },
      icon: {
          iconLayout: 'default#image',
          iconImageHref: '../img/geo.png',
          iconImageSize: [38, 48],
          iconImageOffset: [-15, -62]
      }
  },
  {
      coordinate: [57.397572, 63.771052],
      balloon: {
              balloonContentHeader: 'с. Байкалово',
              balloonContentBody: 'с. Байкалово, ул. Революции, 25',
              hintContent: 'с. Байкалово, ул. Революции, 25'
      },
      icon: {
          iconLayout: 'default#image',
          iconImageHref: '../img/geo_yellow.png',
          iconImageSize: [38, 48],
          iconImageOffset: [-15, -62]
      }
  },
  {
    coordinate: [56.805732, 62.790783],
    balloon: {
            balloonContentHeader: 'д. Баранникова, ул. Пионерская, 12',
            balloonContentBody: 'д. Баранникова, ул. Пионерская, 12',
            hintContent: 'д. Баранникова, ул. Пионерская, 12'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.163278, 59.697803],
    balloon: {
            balloonContentHeader: 'п. Баранчинский',
            balloonContentBody: 'п. Баранчинский, ул. Коммуны, 7',
            hintContent: 'п. Баранчинский, ул. Коммуны, 7'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.759720, 61.415103],
    balloon: {
            balloonContentHeader: 'пгт Белоярский',
            balloonContentBody: 'пгт Белоярский, ул. Милицейская, 3',
            hintContent: 'пгт Белоярский, ул. Милицейская, 3'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.896544, 60.741951],
    balloon: {
            balloonContentHeader: 'г. Березовский',
            balloonContentBody: 'г. Березовский, ул. Героев Труда, 23',
            hintContent: 'г. Березовский, ул. Героев Труда, 23'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.964686, 59.819821],
    balloon: {
            balloonContentHeader: 'Билимбай',
            balloonContentBody: 'Билимбай, пл. Свободы, 2',
            hintContent: 'Билимбай, пл. Свободы, 2'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.675341, 60.980148],
    balloon: {
            balloonContentHeader: 'п. Бобровский',
            balloonContentBody: 'п. Бобровский, пер. Советский, 9',
            hintContent: 'п. Бобровский, пер. Советский, 9'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.856614, 59.047638],
    balloon: {
            balloonContentHeader: 'пгт Бисерть',
            balloonContentBody: 'пгт Бисерть, ул. Революции, 110а',
            hintContent: 'пгт Бисерть, ул. Революции, 110а'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.773198, 62.051793],
    balloon: {
            balloonContentHeader: 'г. Богданович',
            balloonContentBody: 'г. Богданович, ул. Партизанская, 9',
            hintContent: 'г. Богданович, ул. Партизанская, 9'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.714017, 60.775170],
    balloon: {
            balloonContentHeader: 'п. Большой Исток',
            balloonContentBody: 'п. Большой Исток, ул. Ленина, 119А',
            hintContent: 'п. Большой Исток, ул. Ленина, 119А'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [57.263983, 60.139010],
    balloon: {
            balloonContentHeader: 'пгт Верх-Нейвинский',
            balloonContentBody: 'пгт Верх-Нейвинский, ул. 8 Марта, 16А',
            hintContent: 'пгт Верх-Нейвинский, ул. 8 Марта, 16А'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.748844, 61.049291],
    balloon: {
            balloonContentHeader: 'пгт Верхнее Дуброво',
            balloonContentBody: 'пгт Верхнее Дуброво, ул. Клубная, 8',
            hintContent: 'пгт Верхнее Дуброво, ул. Клубная, 8'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.649528, 59.559920],
    balloon: {
            balloonContentHeader: 'пгт Верхние Серги',
            balloonContentBody: 'пгт Верхние Серги, ул. Ленина, 16',
            hintContent: 'пгт Верхние Серги, ул. Ленина, 16'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [57.377416, 59.933206],
    balloon: {
            balloonContentHeader: 'г. Верхний Тагил',
            balloonContentBody: 'г. Верхний Тагил, ул. Маяковского, 17а',
            hintContent: 'г. Верхний Тагил, ул. Маяковского, 17а'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.963734, 60.612494],
    balloon: {
            balloonContentHeader: 'г. Верхняя Пышма',
            balloonContentBody: 'г. Верхняя Пышма, ул. Победы, 11',
            hintContent: 'г. Верхняя Пышма, ул. Победы, 11'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.976834, 60.556673],
    balloon: {
            balloonContentHeader: 'г. Верхняя Пышма',
            balloonContentBody: 'г. Верхняя Пышма, ул. Юбилейная, 20',
            hintContent: 'г. Верхняя Пышма, ул. Юбилейная, 20'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.047473, 60.544150],
    balloon: {
            balloonContentHeader: 'г. Верхняя Салда',
            balloonContentBody: 'г. Верхняя Салда, ул. Карла Маркса, 3',
            hintContent: 'г. Верхняя Салда, ул. Карла Маркса, 3'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [57.979817, 61.661367],
    balloon: {
            balloonContentHeader: 'пгт Верхняя Синячиха',
            balloonContentBody: 'пгт Верхняя Синячиха, ул. Красной Гвардии, 6',
            hintContent: 'пгт Верхняя Синячиха, ул. Красной Гвардии, 6'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.358185, 59.826720],
    balloon: {
            balloonContentHeader: 'г. Верхняя Тура',
            balloonContentBody: 'г. Верхняя Тура, ул. Машиностроителей, 7а',
            hintContent: 'г. Верхняя Тура, ул. Машиностроителей, 7а'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.860518, 60.811543],
    balloon: {
            balloonContentHeader: 'в г. Верхотурье',
            balloonContentBody: 'в г. Верхотурье, ул. Карла Маркса, 2',
            hintContent: 'в г. Верхотурье, ул. Карла Маркса, 2'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: 'г. Волчанск',
            balloonContentBody: 'г. Волчанск, ул. Пионерская, 19',
            hintContent: 'г. Волчанск, ул. Пионерская, 19'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.860355, 60.817687],
    balloon: {
            balloonContentHeader: 'пгт Гари',
            balloonContentBody: 'пгт Гари, ул. Комсомольская, 52',
            hintContent: 'пгт Гари, ул. Комсомольская, 52'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.063088, 59.907649],
    balloon: {
            balloonContentHeader: 'пгт Горноуральский, 39',
            balloonContentBody: 'пгт Горноуральский, 39',
            hintContent: 'пгт Горноуральский, 39'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.696713, 60.087564],
    balloon: {
            balloonContentHeader: 'г. Дегтярск',
            balloonContentBody: 'г. Дегтярск, ул. Калинина, 46',
            hintContent: 'г. Дегтярск, ул. Калинина, 46'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: 'г. Волчанск',
            balloonContentBody: 'г. Волчанск, ул. Пионерская, 19',
            hintContent: 'г. Волчанск, ул. Пионерская, 19'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.860355, 60.817687],
    balloon: {
            balloonContentHeader: 'пгт Гари',
            balloonContentBody: 'пгт Гари, ул. Комсомольская, 52',
            hintContent: 'пгт Гари, ул. Комсомольская, 52'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.063088, 59.907649],
    balloon: {
            balloonContentHeader: 'пгт Горноуральский, 39',
            balloonContentBody: 'пгт Горноуральский, 39',
            hintContent: 'пгт Горноуральский, 39'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.696713, 60.087564],
    balloon: {
            balloonContentHeader: 'г. Дегтярск',
            balloonContentBody: 'г. Дегтярск, ул. Калинина, 46',
            hintContent: 'г. Дегтярск, ул. Калинина, 46'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [50.950094, 43.734686],
    balloon: {
            balloonContentHeader: 'пгт Елань, п/о Порошино, д. 8/1',
            balloonContentBody: 'пгт Елань, п/о Порошино, д. 8/1',
            hintContent: 'пгт Елань, п/о Порошино, д. 8/1'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [57.560543, 62.760860],
    balloon: {
            balloonContentHeader: 'п. Зайково, ул. Коммунистическая, 181',
            balloonContentBody: 'п. Зайково, ул. Коммунистическая, 181',
            hintContent: 'п. Зайково, ул. Коммунистическая, 181'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.802731, 61.312911],
    balloon: {
            balloonContentHeader: 'г. Заречный, ул. Курчатова, 23',
            balloonContentBody: 'г. Заречный, ул. Курчатова, 23',
            hintContent: 'г. Заречный, ул. Курчатова, 23'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [60.703289, 60.415152],
    balloon: {
            balloonContentHeader: 'г. Ивдель, ул. Трошева, 37',
            balloonContentBody: 'г. Ивдель, ул. Трошева, 37',
            hintContent: 'г. Ивдель, ул. Трошева, 37'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_yellow.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.357440, 61.973469],
    balloon: {
            balloonContentHeader: 'г. Каменск-Уральский',
            balloonContentBody: 'г. Каменск-Уральский, ул. Алюминиевая, 43 (Красногорский район)',
            hintContent: 'г. Каменск-Уральский, ул. Алюминиевая, 43 (Красногорский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_red.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: 'г. Волчанск',
            balloonContentBody: 'г. Волчанск, ул. Пионерская, 19',
            hintContent: 'г. Волчанск, ул. Пионерская, 19'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: 'г. Волчанск',
            balloonContentBody: 'г. Волчанск, ул. Пионерская, 19',
            hintContent: 'г. Волчанск, ул. Пионерская, 19'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: '',
            balloonContentBody: '',
            hintContent: ''
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: 'г. Волчанск',
            balloonContentBody: 'г. Волчанск, ул. Пионерская, 19',
            hintContent: 'г. Волчанск, ул. Пионерская, 19'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.860355, 60.817687],
    balloon: {
            balloonContentHeader: 'пгт Гари',
            balloonContentBody: 'пгт Гари, ул. Комсомольская, 52',
            hintContent: 'пгт Гари, ул. Комсомольская, 52'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.063088, 59.907649],
    balloon: {
            balloonContentHeader: 'пгт Горноуральский, 39',
            balloonContentBody: 'пгт Горноуральский, 39',
            hintContent: 'пгт Горноуральский, 39'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.696713, 60.087564],
    balloon: {
            balloonContentHeader: 'г. Дегтярск',
            balloonContentBody: 'г. Дегтярск, ул. Калинина, 46',
            hintContent: 'г. Дегтярск, ул. Калинина, 46'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.798048, 60.560114],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург, ул. Громова, 145,',
            balloonContentBody: 'г. Екатеринбург, ул. Громова, 145, Областной рынок на Громова (Ленинский район)',
            hintContent: 'г. Екатеринбург, ул. Громова, 145, Областной рынок на Громова (Ленинский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.791344, 60.522052],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Краснолесья, 127 (Ленинский район)',
            hintContent: 'г. Екатеринбург, ул. Краснолесья, 127 (Ленинский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.836209, 60.615800],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Малышева, 53, ТРЦ «Антей» (Октябрьский район)',
            hintContent: 'г. Екатеринбург, ул. Малышева, 53, ТРЦ «Антей» (Октябрьский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.823636, 60.505406],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Металлургов, 87, ТЦ «МЕГА» (Верх-Исетский район)',
            hintContent: 'г. Екатеринбург, ул. Металлургов, 87, ТЦ «МЕГА» (Верх-Исетский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.895880, 60.608838],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Победы, 14а, ТЦ «Victory Mall» (Орджоникидзевский район)',
            hintContent: 'г. Екатеринбург, ул. Победы, 14а, ТЦ «Victory Mall» (Орджоникидзевский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.782913, 60.665549],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Рощинская, 21 (Чкаловский район)',
            hintContent: 'г. Екатеринбург, ул. Рощинская, 21 (Чкаловский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.845746, 60.664704],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Учителей, 2б (Кировский район)',
            hintContent: 'г. Екатеринбург, ул. Учителей, 2б (Кировский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [],
    balloon: {
            balloonContentHeader: 'г. Волчанск',
            balloonContentBody: 'г. Волчанск, ул. Пионерская, 19',
            hintContent: 'г. Волчанск, ул. Пионерская, 19'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.860355, 60.817687],
    balloon: {
            balloonContentHeader: 'пгт Гари',
            balloonContentBody: 'пгт Гари, ул. Комсомольская, 52',
            hintContent: 'пгт Гари, ул. Комсомольская, 52'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [58.063088, 59.907649],
    balloon: {
            balloonContentHeader: 'пгт Горноуральский, 39',
            balloonContentBody: 'пгт Горноуральский, 39',
            hintContent: 'пгт Горноуральский, 39'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.696713, 60.087564],
    balloon: {
            balloonContentHeader: 'г. Дегтярск',
            balloonContentBody: 'г. Дегтярск, ул. Калинина, 46',
            hintContent: 'г. Дегтярск, ул. Калинина, 46'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.787617, 59.526009],
    balloon: {
            balloonContentHeader: 'пгт Дружинино',
            balloonContentBody: 'пгт Дружинино, ул. Железнодорожников, 5а',
            hintContent: 'пгт Дружинино, ул. Железнодорожников, 5а'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.829374, 60.672708],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, Дублер Сибирского тракта, 2, ТРК «КомсоМОЛЛ» (Октябрьский район)',
            hintContent: 'г. Екатеринбург, Дублер Сибирского тракта, 2, ТРК «КомсоМОЛЛ» (Октябрьский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.836510, 60.600322],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. 8 Марта, 13 (Ленинский район)',
            hintContent: 'г. Екатеринбург, ул. 8 Марта, 13 (Ленинский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.888510, 60.616375],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Баумана, 5 (Орджоникидзевский район)',
            hintContent: 'г. Екатеринбург, ул. Баумана, 5 (Орджоникидзевский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.842905, 60.631871],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Восточная, 7Д (МФЦ для бизнеса)',
            hintContent: 'г. Екатеринбург, ул. Восточная, 7Д (МФЦ для бизнеса)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.856722, 60.597978],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Героев России, 2, ТДЦ «Свердловск» (Железнодорожный район)',
            hintContent: 'г. Екатеринбург, ул. Героев России, 2, ТДЦ «Свердловск» (Железнодорожный район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  },
  {
    coordinate: [56.850914, 60.570390],
    balloon: {
            balloonContentHeader: 'г. Екатеринбург',
            balloonContentBody: 'г. Екатеринбург, ул. Готвальда, 6/4 (Верх-Исетский район)',
            hintContent: 'г. Екатеринбург, ул. Готвальда, 6/4 (Верх-Исетский район)'
    },
    icon: {
        iconLayout: 'default#image',
        iconImageHref: '../img/geo_grey.png',
        iconImageSize: [38, 48],
        iconImageOffset: [-15, -62]
    }
  }
]


var minSlides;
var maxSlides;
var minSliderFolder, maxSliderFolder;
var minNavSlider = $(window).width();
    minNavSlider =  (minNavSlider < 469) ? 2 : 3;
function windowWidth() {
    if ($(window).width() < 426) {
        minSlides = 3;
        maxSlides = 3;
    }
    else if ($(window).width() < 768) {
        minSlides = 4;
        maxSlides = 4;
    }
    else if ($(window).width() < 1200) {
        minSlides = 5;
        maxSlides = 5;
    }
    else {
        minSlides = 8;
        maxSlides = 8;
    }
}

function resizeFolderSlider() {
  if ($(window).width() < 426) {
      
      minSliderFolder= 1;
      maxSliderFolder =1
  }
  else if ($(window).width() < 768) {
     minSliderFolder = 2;
     maxSliderFolder = 2;
  }
  else if ($(window).width() < 1200) {
    minSliderFolder= 3;
    maxSliderFolder =3;
  }
  else {
    minSliderFolder= 4;
    maxSliderFolder =4;
  }
}

windowWidth();
resizeFolderSlider();


$(document).ready(function(){
  

    $('.banner-slider').bxSlider({
      minSlides: 1,
      maxSlides: 1,
      auto: true,
      speed: 2500,
      pause: 8000
      //slideWidth: ($(window).width() < 426)? 400 : $(window).width() - 20,
    });

    $('.slider').bxSlider();

    $('.news__nav-slider').bxSlider({
     
      slideWidth: 740,
      minSlides: minNavSlider,
      maxSlides: 3,
      slideMargin: 10,
    });

    $('.partener-slider').bxSlider({
      slideWidth: 170,

      // minSlides: 8,
      // maxSlides: 8,
      minSlides: minSlides,
      maxSlides: maxSlides,
      slideMargin: 10,
      stopAutoOnClick: true,
    });

    $('.over-slider').bxSlider({
      slideWidth: ($(window).width() < 376 ) ? 300 : ($(window).width() < 426 ) ? 200 : 380, //380,
      minSlides: ($(window).width() < 376 ) ? 1 : ($(window).width() < 426 ) ? 2 : 3, //3,
      maxSlides: ($(window).width() < 376 ) ? 1 : ($(window).width() < 426 ) ? 2 : 3, //($(window).width() < 426 ) ? 2 : ($(window).width() < 376 ) ? 1 : 3,//3,

      // minSlides: minSlides,
      // maxSlides: maxSlides,

      slideMargin: 10,
      stopAutoOnClick: true,
    });

    $('.newslider').bxSlider({
      //mode: 'fade',
      // captions: true,
      // slideWidth: 600
    });

    $('.pubSlider').bxSlider({
      minSlides: 1,
      maxSlides: 1
    });

    $('.folder-slider').bxSlider({
      slideWidth: 1200,
      maxSlides: minSliderFolder,
      minSlides : maxSliderFolder,
      slideMargin: 100,
      //auto: true
    });

    $("#search-js, #modal-btn-js, #modal-js").click(function(){
      $("#modal-js").slideToggle(); 
    });

    $('.calender-js').click(function(){
      $("#modal-calender-js").slideDown();
    });

    $(".send-code").click(function(){
      $("#modal-send-js").slideDown();
    });

    $("#modal-close-js").click(function(){
      $("#modal-send-js").slideUp();
    });

    $("#doc-js").click(function(){
      $("#modal-doc-js").slideDown();
    })
    $("#modal-close-js").click(function(){
      $("#modal-doc-js").slideUp();
    });

    $("#modal-close-calender-js").click(function(){
      $("#modal-calender-js").slideUp();
    });

    //OFFERS JS
    $(".offers-title-js").bind({
      click: function() {
        const $this = $(this);
        const $list = $(this).closest(".offers__list-item").find(".offers__sublist");
        const $plus_icon =  $(this).find(".offers__plus-icon");
        const $minus_icon =  $(this).find(".offers__minus-icon");

        $list.slideToggle("fast", function(){
          if($(this).is(":visible")) {
            $this.addClass("offers__list-title--active");
            $plus_icon.css({opacity: 0});
            $minus_icon.css({opacity: 1});
            
          } else {
            $this.removeClass("offers__list-title--active");
            $plus_icon.css({opacity: 1});
            $minus_icon.css({opacity: 0});
          }
        });
      }
    });

    //SIDEBAR JS
    $(".sidebar__sub-title").click(function(){
      const $this = $(this);
      const $subList = $(this).closest(".sidebar__item").find(".sidebar__sub-list");
      $subList.slideToggle("fast", function() {
        if($subList.is(":visible")) {
          $this.find(".sidebar__fas-icon").css({
            transform: "translate(20px, 5px) rotate(-180deg)",
            "transform-origin": "center",
          })
        } else {
          $this.find(".sidebar__fas-icon").css({
            transform: "translate(0px, 5px) rotate(0deg)",
            "transform-origin": "center",
          })
        } 
      }); 
    });

    //SLIDE MENU
    $(".header__burger-svg--bars").click(function(){
      const $this = $(this);
      $(".header__modal-menu").slideDown("fast", function(){
        $this.css({display: "none"});
        $(".header__burger-svg--close").css({display: "block"});
      }) 
    });

    $(".header__burger-svg--close").click(function(){
      const $this = $(this);
      $(".header__modal-menu").slideUp("fast", function(){
        $this.css({display: "none"});
        $(".header__burger-svg--bars").css({display: "block"});
      }) 
    });

    $(".header__modal-menu").click(function(){
      $(this).slideUp("fast", function(){
        $(".header__burger-svg--close").css({display: "none"});
        $(".header__burger-svg--bars").css({display: "block"});
      }) 
    })



    $(".sidebar__label-service").click(function(e){
     // e.preventDefault();
      const $nextNode = $(this).next();
      const $this = $(this);
      $nextNode.slideToggle("fast", function(){
        if($nextNode.is(":visible")) {
          $this.find(".sidebar__minus-icon").css({opacity: 1});
          $this.find(".sidebar__plus-icon").css({opacity: 0});
        } else {
          $this.find(".sidebar__minus-icon").css({opacity: 0});
          $this.find(".sidebar__plus-icon").css({opacity: 1});
        }
      })
    })

    //slide filter on 1024 adaptive
    $(".our-services__filter-btn").bind({
      click: function () {
        const $plus = $(this).find(".our-services__filter-icon-plus");
        const $minus = $(this).find(".our-services__filter-icon-minus");
        const $sidebar = $(".our-services__sidebar");
        
        if($plus.is(":visible")) {
          $sidebar.css({ left: 0 });
          $plus.css({display: "none"}); 
          $minus.css({display: "inline-block"})
        } else {
          $sidebar.css({ left: "-110%" });
          $plus.css({display: "inline-block"}); 
          $minus.css({display: "none"})
        }
      }
    });

    $(".our-services__name").click(function(){
      const $this = $(this);
      const $filter_list = $(".our-services__filter-list");
      const $icon = $this.find(".our-services__sort-icon");
      if($filter_list.is(":visible")) {
        $filter_list.css({
          display: "none",
          height: "2px",
          opacity: 0,
        });
        $icon.css({transform: "rotate(0deg)"})

      } else {
        $filter_list.css({
          display: "block",
          height: "initial",
          opacity: 1, 
        });
        $icon.css({transform: "rotate(180deg)"})
      }
      //$(this).next()
    });

    //SHOW MORE
    $("#show-more-js").click(function() {
      $(".offices__show-more").css({display: "none"});
      $(".offices__portrait-list").css({height: "auto"})
    });

    //TAB
    $("#tab-btns .offices__map-btn").each(function(ind, elt){
      const $this = $(elt);
      $this.click(function(){
       
        const dataAttr = $this.data("tab");
        const $closet = $this.closest(".offices__cart-map");

        $("#tab-btns .offices__map-btn").removeClass("offices__map-btn--active")
        $this.addClass("offices__map-btn--active")

        if(dataAttr === "tab-slide") {
          //alert(dataAttr)
          $(document).ready(()=> $(".officeSlider").bxSlider({}))
        } 

        $closet.find(".tab").removeClass("tab--active");
        $closet.find("."+dataAttr).addClass("tab--active");
      })
      
    })


    $("#map-svg path").each(function(){
      let color = $(this).attr("fill");
        $(this).hover(
            function(){
              $(this).css({fill: "#F49678"})
            }, function(){
              $(this).css({fill: color})
            }
        );
       
        // $(this).mousemove((e) => {
        //   console.log(e.clientX, "X : Y", e.clientY)
        //   $("#tooltip").text($(this).data("city")).css({
        //     left: e.offsetX-60,
        //     top: e.offsetY + 30,
        //     zIndex: 99999,
        //     opacity:1
           
        //   })
        // });

        // $(this).mouseout(function(){
        //   $("#tooltip").text("").css({
        //     left: 0,
        //     top: 0,
        //     zIndex: 99999,
        //     opacity:0
           
        //   })
        // })



        $(this)
          .mouseenter((e) => {
              console.log(e.clientX, "X : Y", e.clientY)
              $("#tooltip").text($(this).data("city")).css({
                left: e.offsetX-60,
                top: e.offsetY + 30,
                zIndex: 99999,
                opacity:1
              
             })
          })
          .mouseout(function(){
            $("#tooltip").text("").css({
              left: 0,
              top: 0,
              zIndex: 99999,
              opacity:0
             
            })
          });


        $(this).click(function(){
          if($(this).data("city")) {
            console.log(cities);
            //alert($(this).data("city"));
            let city = $(this).data("city");
                city = cities.find(c => c.cityName === city )

                //city && city.offices.forEach(office => {

                  // const {coordinates} = office;
                  // const current_city = office.city;
                  //const current_name = office.name;
                

                  const {coordinates} = city.offices[0]
                  const current_city = city.offices[0].city;
                  const current_name = city.offices[0].name;
                  console.log("coordinates:", current_city);
                  ymaps.ready(init);
  
                  function init () {
                    $(".info__yandex-map").css({display: "block" });
                    $(".info__map").css({display: "none" });
                      var myMap = new ymaps.Map('map', {
                          //center: [56.829374, 60.672699], 
                          center: coordinates,
                          zoom: 10
                      }, {
                          searchControlProvider: 'yandex#search'
                      });
                      
                      // Создаём макет содержимого.
                      // MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                      //     '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
                      // ),

                      // myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                      //     hintContent: current_name,
                      //     balloonContent: current_city
                      // }, {
                      //     iconLayout: 'default#image',
                      //     iconImageHref: '../img/geo.png',
                      //     iconImageSize: [40, 48],
                      //     iconImageOffset: [-5, -38]
                      // }),

                      // myMap.geoObjects.add(myPlacemark)



                      city.offices.forEach(office => {
                        console.log(office)
                          const myPlacemark = new ymaps.Placemark(office.coordinates, office.balloon, office.icon);
                          myMap.geoObjects.add(myPlacemark);
                      })
          
                  }

              //})


          } else {
            alert("no Data address")
          }
        })
    });

    //MAP OFFICES
    ymaps.ready(init);

    function init(){
      var myMapOffices = new ymaps.Map("map-offices", {
                  center: [56.829374, 60.672699],
                  zoom: 7
                }, {
                  searchControlProvider: 'yandex#search'
              });
    
            myMapOffices.behaviors.disable(["drag", "scrollZoom"]);


            dataLocation.forEach(elt => {
              console.log(elt)
                const myPlacemark = new ymaps.Placemark(elt.coordinate, elt.balloon, elt.icon);
                myMapOffices.geoObjects.add(myPlacemark);
            })



    }








    //FOR ONE OFFICE
    // function myMapOffice1() {
    //   ymaps.ready(init);
    //   function init(){
    //       var myMapOffice = new ymaps.Map("map-office", {
    //                 center: [56.829374, 60.672699],
    //                 zoom: 4
    //             }, {
    //               searchControlProvider: 'yandex#search'
    //           });

    //           myMapOffice.behaviors.disable(["drag", "scrollZoom"]);
    //       }

    //       const myPlacemark = new ymaps.Placemark(
    //               [56.829374, 60.672699], 
                  
    //               {
    //                   iconLayout: 'default#image',
    //                   iconImageHref: '../img/geo.png',
    //                   iconImageSize: [40, 48],
    //                   iconImageOffset: [-5, -38]
    //               }
    //             );
    //             myMapOffice.geoObjects.add(myPlacemark);
    // }
    // myMapOffice1();







    ymaps.ready(function () {
      var myMap = new ymaps.Map('map-office', {
              center: [56.829374, 60.672699],//[55.751574, 37.573856],
              zoom: 9
          }, {
              searchControlProvider: 'yandex#search'
          }),
          
  
          // Создаём макет содержимого.
          MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
              '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
          ),
  
          myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
              hintContent: 'Собственный значок метки',
              balloonContent: 'Это красивая метка'
          }, {
              // Опции.
              // Необходимо указать данный тип макета.
              iconLayout: 'default#image',
              // Своё изображение иконки метки.
              //iconImageHref: 'images/myIcon.gif',
              iconImageHref: '../img/geo.png',

              iconImageSize: [40, 48],
              iconImageOffset:[-50, -75] //[-5, -38]

              // Размеры метки.
              //iconImageSize: [30, 42],
              // Смещение левого верхнего угла иконки относительно
              // её "ножки" (точки привязки).
              //iconImageOffset: [-5, -38]
          }),
  
          myPlacemarkWithContent = new ymaps.Placemark([55.661574, 37.573856], {
              hintContent: 'Собственный значок метки с контентом',
              balloonContent: 'А эта — новогодняя',
              iconContent: '12'
          }, {
              // Опции.
              // Необходимо указать данный тип макета.
              iconLayout: 'default#imageWithContent',
              // Своё изображение иконки метки.
              iconImageHref: 'images/ball.png',
              // Размеры метки.
              iconImageSize: [48, 48],
              // Смещение левого верхнего угла иконки относительно
              // её "ножки" (точки привязки).
              iconImageOffset: [-24, -24],
              // Смещение слоя с содержимым относительно слоя с картинкой.
              iconContentOffset: [15, 15],
              // Макет содержимого.
              iconContentLayout: MyIconContentLayout
          });
  
      myMap.geoObjects
          .add(myPlacemark)
          .add(myPlacemarkWithContent);
  });











    //LEVEL-js

    $(".user__inner-form .user__inner-btn--form").each(function(ind, btn){
      console.log(btn);
      const $this = $(btn);
      $this.click(function(){
        $(`.user__list-number .user__item-number:nth-child(${ind+2})`).addClass("user__item-number--success");
        $(".user__form-level").removeClass("level-js");
        $(`.user__form-level:nth-child(${ind+2})`).addClass("level-js");

      })
    })

    $(".user__list-number .user__item-number").each(function(ind, elt){
      const $this = $(elt);
      const $ind = ind;
      $this.click(function(){
        $(".user__form-level").removeClass("level-js");
        $(`.user__form-level:nth-child(${ind+1})`).addClass("level-js");
        
        $(".user__list-number .user__item-number").each((ind,item) => {
          if(ind >$ind) {
            $(item).removeClass("user__item-number--success");
          }
        })
      })
    })


    //USER TAB
    const tabs = document.querySelectorAll(".user__tab-item");

    tabs.forEach((item, id) => {
      item.addEventListener("click", function() {
        const elt = this.dataset.tab;

        tabs.forEach(tab => tab.classList.remove("user__tab-item--active"));
        this.classList.add("user__tab-item--active");
        document.querySelectorAll(".user__tab-content").forEach(el => {
          el.classList.add("hide");
          if(el.classList.contains(elt)) {
            el.classList.remove("hide")
          }
        })
      })
    })
    


}); //end ready










//CALENDAR


let calendar = document.querySelector('.calendar')

const month_names = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

isLeapYear = (year) => {
    return (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) || (year % 100 === 0 && year % 400 ===0)
}

getFebDays = (year) => {
    return isLeapYear(year) ? 29 : 28
}

generateCalendar = (month, year) => {

    let calendar_days = calendar.querySelector('.calendar-days')
    let calendar_header_year = calendar.querySelector('#year')

    let days_of_month = [31, getFebDays(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]

    calendar_days.innerHTML = ''

    let currDate = new Date()
    if (!month) month = currDate.getMonth()
    if (!year) year = currDate.getFullYear()

    let curr_month = `${month_names[month]}`
    month_picker.innerHTML = curr_month
    calendar_header_year.innerHTML = year

    // get first day of month
    
    let first_day = new Date(year, month, 1)

    for (let i = 0; i <= days_of_month[month] + first_day.getDay() - 1; i++) {
        let day = document.createElement('div')
        if (i >= first_day.getDay()) {
            day.classList.add('calendar-day-hover')
            day.innerHTML = i - first_day.getDay() + 1
            day.innerHTML += `<span></span>
                            <span></span>
                            <span></span>
                            <span></span>`
            if (i - first_day.getDay() + 1 === currDate.getDate() && year === currDate.getFullYear() && month === currDate.getMonth()) {
                day.classList.add('curr-date')
            }
        }
        calendar_days.appendChild(day)
    }
}

let month_list = calendar.querySelector('.month-list')

month_names.forEach((e, index) => {
    let month = document.createElement('div')
    month.innerHTML = `<div data-month="${index}">${e}</div>`
    month.querySelector('div').onclick = () => {
        month_list.classList.remove('show')
        curr_month.value = index
        generateCalendar(index, curr_year.value)
    }
    month_list.appendChild(month)
})

let month_picker = calendar.querySelector('#month-picker')

month_picker.onclick = () => {
    month_list.classList.add('show')
}

let currDate = new Date()

let curr_month = {value: currDate.getMonth()}
let curr_year = {value: currDate.getFullYear()}

generateCalendar(curr_month.value, curr_year.value)

document.querySelector('#prev-year').onclick = () => {
    --curr_year.value
    generateCalendar(curr_month.value, curr_year.value)
}

document.querySelector('#next-year').onclick = () => {
    ++curr_year.value
    generateCalendar(curr_month.value, curr_year.value)
}

let dark_mode_toggle = document.querySelector('.dark-mode-switch')

dark_mode_toggle.onclick = () => {
    document.querySelector('body').classList.toggle('light')
    document.querySelector('body').classList.toggle('dark')
}