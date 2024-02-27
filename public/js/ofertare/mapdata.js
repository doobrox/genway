var simplemaps_countrymap_mapdata={
  main_settings: {
   //General settings
    width: "responsive", //'700' or 'responsive'
    background_color: "#FFFFFF",
    background_transparent: "yes",
    border_color: "#ffffff",
    
    //State defaults
    state_description: "State description",
    state_color: "#88A4BC",
    state_hover_color: "#3B729F",
    state_url: "",
    border_size: 1.5,
    all_states_inactive: "no",
    all_states_zoomable: "yes",
    
    //Location defaults
    location_description: "Location description",
    location_url: "",
    location_color: "#FF0067",
    location_opacity: 0.8,
    location_hover_opacity: 1,
    location_size: 12,
    location_type: "square",
    location_image_source: null,
    location_border_color: "#FFFFFF",
    location_border: 2,
    location_hover_border: 2.5,
    all_locations_inactive: "no",
    all_locations_hidden: "no",
    
    //Label defaults
    label_color: "#d5ddec",
    label_hover_color: "#d5ddec",
    label_size: "18",
    label_font: "Arial",
    hide_labels: "no",
    hide_eastern_labels: "no",
   
    //Zoom settings
    zoom: "yes",
    manual_zoom: "yes",
    back_image: "no",
    initial_back: "no",
    initial_zoom: "-1",
    initial_zoom_solo: "no",
    region_opacity: 1,
    region_hover_opacity: 0.6,
    zoom_out_incrementally: "yes",
    zoom_percentage: 0.99,
    zoom_time: 0.5,
    
    //Popup settings
    popup_color: "white",
    popup_opacity: 0.9,
    popup_shadow: 1,
    popup_corners: 5,
    popup_font: "12px/1.5 Verdana, Arial, Helvetica, sans-serif",
    popup_nocss: "no",
    
    //Advanced settings
    div: "map",
    auto_load: "yes",
    url_new_tab: "no",
    images_directory: "default",
    fade_time: 0.1,
    link_text: "View Website",
    popups: "detect",
    state_image_url: "",
    state_image_position: "",
    location_image_url: ""
  },
  state_specific: {
    ROU122: {
      name: "Dolj",
    },
    ROU123: {
      name: "Gorj",
    },
    ROU124: {
      name: "Mehedinti",
    },
    ROU126: {
      name: "Olt",
    },
    ROU127: {
      name: "Teleorman",
    },
    ROU128: {
      name: "Bucharest",
    },
    ROU129: {
      name: "Calarasi",
    },
    ROU130: {
      name: "Dâmbovita",
    },
    ROU131: {
      name: "Giurgiu",
    },
    ROU132: {
      name: "Ialomita",
    },
    ROU133: {
      name: "Constanta",
    },
    ROU276: {
      name: "Arad",
    },
    ROU277: {
      name: "Bihor",
    },
    ROU278: {
      name: "Caras-Severin",
    },
    ROU280: {
      name: "Timis",
    },
    ROU287: {
      name: "Botosani",
    },
    ROU294: {
      name: "Alba",
    },
    ROU295: {
      name: "Bistrita-Nasaud",
    },
    ROU296: {
      name: "Cluj",
    },
    ROU297: {
      name: "Hunedoara",
    },
    ROU298: {
      name: "Maramures",
    },
    ROU299: {
      name: "Mures",
    },
    ROU300: {
      name: "Salaj",
    },
    ROU301: {
      name: "Satu Mare",
    },
    ROU302: {
      name: "Arges",
    },
    ROU303: {
      name: "Sibiu",
    },
    ROU304: {
      name: "Vâlcea",
    },
    ROU305: {
      name: "Brasov",
    },
    ROU306: {
      name: "Covasna",
    },
    ROU307: {
      name: "Harghita",
    },
    ROU308: {
      name: "Iasi",
    },
    ROU309: {
      name: "Neamt",
    },
    ROU310: {
      name: "Prahova",
    },
    ROU311: {
      name: "Suceava",
    },
    ROU312: {
      name: "Bacau",
    },
    ROU313: {
      name: "Braila",
    },
    ROU314: {
      name: "Buzau",
    },
    ROU315: {
      name: "Galati",
    },
    ROU316: {
      name: "Vaslui",
    },
    ROU317: {
      name: "Vrancea",
    },
    ROU4844: {
      name: "Ilfov",
    },
    ROU4847: {
      name: "Tulcea",
    }
  },
  /*locations: {
    "0": {
      x: 360.2,
      y: 625
    },
    "1": {
      x: "329",
      y: "507.1"
    },
    "2": {
      x: "283.1",
      y: "593.2"
    },
    "3": {
      x: "446.5",
      y: "596.7"
    },
    "4": {
      x: "527.2",
      y: "651.5"
    },
    "5": {
      x: "617.2",
      y: "589.8"
    },
    "6": {
      x: "683",
      y: "605.4"
    },
    "7": {
      x: "558.1",
      y: "539"
    },
    "8": {
      x: "604.3",
      y: "633.6"
    },
    "9": {
      x: "787.8",
      y: "559.8"
    },
    "10": {
      x: "847.6",
      y: "639.1"
    },
    "11": {
      x: "163.9",
      y: "307.4"
    },
    "12": {
      x: "203.8",
      y: "212.6"
    },
    "13": {
      x: "190.2",
      y: "482.8"
    },
    "14": {
      x: "102.9",
      y: "402.9"
    },
    "15": {
      x: "678.8",
      y: "52.1"
    },
    "16": {
      x: "347.9",
      y: "330.4"
    },
    "17": {
      x: "450.7",
      y: "164.2"
    },
    "18": {
      x: "366.5",
      y: "237.4"
    },
    "19": {
      x: "286.5",
      y: "396.6"
    },
    "20": {
      x: "392.2",
      y: "94.2"
    },
    "21": {
      x: "460",
      y: "274.7"
    },
    "22": {
      x: "301.4",
      y: "182.9"
    },
    "23": {
      x: "266.6",
      y: "108.8"
    },
    "24": {
      x: "489.2",
      y: "459"
    },
    "25": {
      x: "427.3",
      y: "380.7"
    },
    "26": {
      x: "415.7",
      y: "489"
    },
    "27": {
      x: "521.7",
      y: "380.7"
    },
    "28": {
      x: "617",
      y: "369.3"
    },
    "29": {
      x: "561.9",
      y: "275.1"
    },
    "30": {
      x: "744.3",
      y: "157.9"
    },
    "31": {
      x: "636",
      y: "203.8"
    },
    "32": {
      x: "622.2",
      y: "504.4"
    },
    "33": {
      x: "563.5",
      y: "109.9"
    },
    "34": {
      x: "700.5",
      y: "293.5"
    },
    "35": {
      x: "785.7",
      y: "490.5"
    },
    "36": {
      x: "686.7",
      y: "461.8"
    },
    "37": {
      x: "795.8",
      y: "387.6"
    },
    "38": {
      x: "800.6",
      y: "269.7"
    },
    "39": {
      x: "708",
      y: "384.1"
    },
    "40": {
      x: "625.1",
      y: "578.9"
    },
    "41": {
      x: "872.8",
      y: "504.2"
    }
  },*/
  labels: {
    "0": {
      name: "Dolj",
      parent_id: "ROU122",
      x: 360.2,
      y: 625
    },
    "1": {
      name: "Gorj",
      parent_id: "ROU123",
      x: 329,
      y: 507.1
    },
    "2": {
      name: "Mehedinti",
      parent_id: "ROU124",
      x: 283.1,
      y: 593.2
    },
    "3": {
      name: "Olt",
      parent_id: "ROU126",
      x: 446.5,
      y: 596.7
    },
    "4": {
      name: "Teleorman",
      parent_id: "ROU127",
      x: 527.2,
      y: 651.5
    },
    "5": {
      name: "Bucharest",
      parent_id: "ROU128",
      x: 617.2,
      y: 589.8
    },
    "6": {
      name: "Calarasi",
      parent_id: "ROU129",
      x: 683,
      y: 605.4
    },
    "7": {
      name: "Dâmbovita",
      parent_id: "ROU130",
      x: 558.1,
      y: 539
    },
    "8": {
      name: "Giurgiu",
      parent_id: "ROU131",
      x: 604.3,
      y: 633.6
    },
    "9": {
      name: "Ialomita",
      parent_id: "ROU132",
      x: 787.8,
      y: 559.8
    },
    "10": {
      name: "Constanta",
      parent_id: "ROU133",
      x: 847.6,
      y: 639.1
    },
    "11": {
      name: "Arad",
      parent_id: "ROU276",
      x: 163.9,
      y: 307.4
    },
    "12": {
      name: "Bihor",
      parent_id: "ROU277",
      x: 203.8,
      y: 212.6
    },
    "13": {
      name: "Caras-Severin",
      parent_id: "ROU278",
      x: 190.2,
      y: 482.8
    },
    "14": {
      name: "Timis",
      parent_id: "ROU280",
      x: 102.9,
      y: 402.9
    },
    "15": {
      name: "Botosani",
      parent_id: "ROU287",
      x: 678.8,
      y: 52.1
    },
    "16": {
      name: "Alba",
      parent_id: "ROU294",
      x: 347.9,
      y: 330.4
    },
    "17": {
      name: "Bistrita-Nasaud",
      parent_id: "ROU295",
      x: 450.7,
      y: 164.2
    },
    "18": {
      name: "Cluj",
      parent_id: "ROU296",
      x: 366.5,
      y: 237.4
    },
    "19": {
      name: "Hunedoara",
      parent_id: "ROU297",
      x: 286.5,
      y: 396.6
    },
    "20": {
      name: "Maramures",
      parent_id: "ROU298",
      x: 392.2,
      y: 94.2
    },
    "21": {
      name: "Mures",
      parent_id: "ROU299",
      x: 460,
      y: 274.7
    },
    "22": {
      name: "Salaj",
      parent_id: "ROU300",
      x: 301.4,
      y: 182.9
    },
    "23": {
      name: "Satu Mare",
      parent_id: "ROU301",
      x: 266.6,
      y: 108.8
    },
    "24": {
      name: "Arges",
      parent_id: "ROU302",
      x: 489.2,
      y: 459
    },
    "25": {
      name: "Sibiu",
      parent_id: "ROU303",
      x: 427.3,
      y: 380.7
    },
    "26": {
      name: "Vâlcea",
      parent_id: "ROU304",
      x: 415.7,
      y: 489
    },
    "27": {
      name: "Brasov",
      parent_id: "ROU305",
      x: 521.7,
      y: 380.7
    },
    "28": {
      name: "Covasna",
      parent_id: "ROU306",
      x: 617,
      y: 369.3
    },
    "29": {
      name: "Harghita",
      parent_id: "ROU307",
      x: 561.9,
      y: 275.1
    },
    "30": {
      name: "Iasi",
      parent_id: "ROU308",
      x: 744.3,
      y: 157.9
    },
    "31": {
      name: "Neamt",
      parent_id: "ROU309",
      x: 636,
      y: 203.8
    },
    "32": {
      name: "Prahova",
      parent_id: "ROU310",
      x: 622.2,
      y: 504.4
    },
    "33": {
      name: "Suceava",
      parent_id: "ROU311",
      x: 563.5,
      y: 109.9
    },
    "34": {
      name: "Bacau",
      parent_id: "ROU312",
      x: 700.5,
      y: 293.5
    },
    "35": {
      name: "Braila",
      parent_id: "ROU313",
      x: 785.7,
      y: 490.5
    },
    "36": {
      name: "Buzau",
      parent_id: "ROU314",
      x: 686.7,
      y: 461.8
    },
    "37": {
      name: "Galati",
      parent_id: "ROU315",
      x: 795.8,
      y: 387.6
    },
    "38": {
      name: "Vaslui",
      parent_id: "ROU316",
      x: 800.6,
      y: 269.7
    },
    "39": {
      name: "Vrancea",
      parent_id: "ROU317",
      x: 708,
      y: 384.1
    },
    "40": {
      name: "Ilfov",
      parent_id: "ROU4844",
      x: 625.1,
      y: 578.9
    },
    "41": {
      name: "Tulcea",
      parent_id: "ROU4847",
      x: 872.8,
      y: 504.2
    }
  },
  legend: {
    entries: [
      {
        name: "Sistem montat complet",
        color: "#00af50",
        type: "state",
        shape: "circle",
        ids: "0, 1"
      },
      {
        name: "Sistem nemontat",
        color: "#fc0003",
        type: "state",
        shape: "square",
        ids: "1"
      },
      {
        name: "Invertor lipsa + alte componente lipsa",
        color: "#ffff01",
        type: "state",
        shape: "triangle",
        ids: "2"
      },
      {
        name: "Contract anulat",
        color: "#c55911",
        type: "state",
        shape: "diamond",
        ids: ""
      }
    ]
  },
  regions: {},
  data: {
    data: {}
  }
};