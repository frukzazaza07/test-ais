import currency from 'currency.js'
import rules from './rules.helper'

export const setSequence = (index, paginate) => {
  return index + (paginate.current_page - 1) * paginate.per_page + 1
}

export const currencyTh = (value, digit = 2) => {
  return currency(value, { symbol: '', precision: digit }).format()
}

export default () => {
  return { setSequence, currencyTh, ...rules() }
}
