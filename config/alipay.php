<?php
return [
    'app_id' => '2018010901712488',
    'notify_url' => 'http://app.szsousou.com/api/order/ali-notify',
    'return_url' => 'http://app.szsousou.com/api/order/ali-return',
    //'notify_url' => 'http://s.38sd.com/api/order/ali-notify',
    //'return_url' => 'http://s.38sd.com/api/order/ali-return',
//    'notify_url' => 'http://s.38sd.com:/api/order/ali-notify',
//    'return_url' => 'http://s.38sd.com/api/order/ali-return',
    'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgbDcTOS9sdcWhyShMADS6wVb1MGa2XTSjbL6yIQi9e8IeLfb5LKxCoFGz1Pox9X1JaAa4vBHYL7vKEYteVqSGftgSIFpoxgp6nx2a8JheqV+CJLoZ1fGeC1A3NBDPixi1NFTbl6bK+CrkNUhX1Xld4vhVD8ldnqlB1T2PoUh13AXgOMEnt0Jyd4sRV5UOYUkM4XkJgF5zMwqDGPsAvU4eY2TFboMEYRbYQydtgF7OJO1llRiRUqH/vWm1ZaRaQVjj/J/xSU4ljyohZu5KccjInvZmpGJAYne/1aawBjwNSFWmSJAIPLnhIXviKgo9X9lthoPiu4u1/D2J6MrWkebawIDAQAB',
    'private_key' => 'MIIEogIBAAKCAQEAu5XRfrmagaARnX2DJBkkEXmAbFnrNb+wmFA0GRsSxjzalPI0XrVp7mkVQrkwly/mT1hEOLLhQdczXfEoVu0+dDfO2zavXWvo1ymk6NZNSs9iPwMYow7w6L43iBrn3ip9Acbn5jm72rN+gFtMa/vPUzHFbb8jDuAzfiycKtioAS118WjopbjzUhHzc7xm9s+uY6tmGSwhT1RUT24jIL9qUXY1HnJnZDVw1lCw+vHZuJLs283XrtsXDcAEk6HOkKIUb4YdIKRpgDqoNhnJZpSYdLg+RdRh4xi4X/xCD/BCavgR0so+6Q1SSEGpAejQg13zZdfwQGu5uW2eGPmJKs8L8wIDAQABAoIBACqZbFjuB3N2iWmNjlGNUA6T3CE/DlZHcPA2Do7BAjN4sF7w3gnAAw18WbKsYaeD3jaYoe2KWfNczrJ/yJxtBijYX6aStaJulhL+xw+FU0ow7GvI5ThqdTU1tWC4U321gmv74r/6znltF8ZIdYN0TPHktKQmqux+59K4iMKGLykM6T9/OJxtnvVC4SaUNMn7lX9u4Omu0FVpwiLDKeUNMdXKUUdtm6U6BG1VTjzJftiBfDPHSel8d+YaBixXdMGpFcN/ZrE++vJDhwPMADoJ07/wsfFmAYtvLKeH7ZSU/YnS4JP5/ckbDsHKOWLpzGtGNvShlqSARMncGS7WNV66MPkCgYEA5Ymle8Rvpsih4PWHR85wrGTbaubU3MPF9H6pwXtX8t61H8UUheO7KPUYCeHkwRU09CfEZ392e3U7xTubtOJTbUkBOvMZ9SWLLwk9Y9x/V1OxYb/KKICe+qMRvFGXLpTd2Br+ZaI6Gjl/eeYHxJ0xTSGjgYLPX4b/11+AFpB+RO0CgYEA0TYHF26sVbCSjYXN29j7nPUWZT9QpSiHhNcF58HeuM/KeU64qmSrclwb0HbaFbsvPeo2BzHzCpDUQs/nHrOXMuFIa1fpgMTgD5dIPtY67Yl2bZOcvejWChAoelkK3Pxvmn4LFUWA2LdPUFarGVTrb+oY9Ui3JXCYNcHK+d8KWF8CgYBNYBUUn6czhbZr6thrcD+zHVyJsL5YnPYB8SCwRC/lgwVzbC/64kWZDOXJjwhZvhhXpYAjk2gRPfekCO/4DcMpt85eXMyHQtykQm0bWJzkhyXoWYqAFLuCqa202Zwo663Mx2yyPflwFanPIafEs2iHI6vGYJ4fCZb8twiqkpK+EQKBgBI1aZiYgVdT7u1yX+Bqmjum+TQGtrYpOOJO9KyfKwtaynyUZW4vJNznN2SFc6XAjPQOlnU527VntZG7FpqNdvNuuCbfl+slN3sj553Qd4CFHlGbL2E4CqkFjwBXD6L8NZp0rfJEAlraWYTchjfGNFehQFrY4VfY3OaQ32GwD+5xAoGASUpONASTXwUoH5ML7AxgdQIBPmP6xBoj2ACDCNY/44wAAnkCVNeG1dASK9SfmlCaurGjq4aaO9PeXe7mkiA/VDyuf0WUFgeR8pcSMNltoMpX9JaQBRNpwOUgHrbn/H4TixEXSnQRKA+mhaBJvlSbJl7E7kXgdoZtg2sdLz3sHuY=',
    'log' => [
        'file' => './storage/logs/alipay.log',
        'level' => 'debug'
    ],
];