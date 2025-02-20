export const validateUppercaseString = (number = true) => {
  let regex = /^[A-Z]+$/
  let duplicateCheck = /(?!.*([A-Z]).*\1)/
  if (number) {
    regex = /^[A-Z0-9]+$/
    duplicateCheck = /^[A-Z0-9]+$/
  }
  return [
    (input) =>
      !input
        ? true
        : regex.test(input) && duplicateCheck.test(input)
          ? true
          : `Please input only A-Z${number ? ', 0-9' : ''}(UPPERCASE).`,
  ]
}

export const validateThaiCharString = (number = true) => {
  let regex = /^[ก-๙ \s]+$/
  let duplicateCheck = /(?!.*([ก-๙ \s]).*\1)/
  if (number) {
    regex = /^[ก-๙ 0-9\s]+$/
    duplicateCheck = /^[ก-๙ 0-9\s]+$/
  }
  return [
    (input) =>
      !input
        ? true
        : regex.test(input) && duplicateCheck.test(input)
          ? true
          : `Please input only ตัวอักษรภาษาไทย${number ? ', 0-9' : ''}.`,
  ]
}

export const validateEngCharString = (number = true) => {
  let regex = /^[a-zA-Z ]+$/
  let duplicateCheck = /(?!.*([a-zA-Z ]).*\1)/
  if (number) {
    regex = /^[a-zA-Z 0-9]+$/
    duplicateCheck = /^[a-zA-Z 0-9]+$/
  }
  return [
    (input) =>
      !input
        ? true
        : regex.test(input) && duplicateCheck.test(input)
          ? true
          : `Please input only a-z, A-Z${number ? ', 0-9' : ''}.`,
  ]
}

export const max = (max, message = null) => [
  (value) => String(value).length <= max || (message ? message : `ระบุไม่เกิน ${max} ตัวอักษร`),
]

export const required = () => [(value) => !!value || value === 0 || 'กรุณากรอกข้อมูล']

export default () => {
  return {
    rules: {
      validateUppercaseString,
      max,
      required,
      validateThaiCharString,
      validateEngCharString,
    },
  }
}
