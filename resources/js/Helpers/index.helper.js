export const setSequence = (index, paginate) => {
    return index + (paginate.current_page - 1) * paginate.per_page + 1;
};

function validateUppercaseString() {
    const regex = /^[A-Z]+$/;
    const duplicateCheck = /(?!.*([A-Z]).*\1)/;
    return [(input) => regex.test(input) && duplicateCheck.test(input) ? true : 'Please input only A-Z(UPPERCASE).']
}

export default () => {
    return { setSequence, validateUppercaseString };
};