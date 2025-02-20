export const validateUppercaseString = () => {
    const regex = /^[A-Z]+$/;
    const duplicateCheck = /(?!.*([A-Z]).*\1)/;
    return [(input) => !input ? true : regex.test(input) && duplicateCheck.test(input) ? true : 'Please input only A-Z(UPPERCASE).']
}

export const max = (max, message = null) => [
    (value) =>
        String(value).length <= max ||
        (message ? message : `ระบุไม่เกิน ${max} ตัวอักษร`),
];

export const required = () => [
    (value) => !!value || value === 0 || "กรุณากรอกข้อมูล",
];

export default () => {
    return { rules: { validateUppercaseString, max, required } };
};