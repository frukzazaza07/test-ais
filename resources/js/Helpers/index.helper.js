export const setSequence = (index, paginate) => {
    return index + (paginate.current_page - 1) * paginate.per_page + 1;
};

export default () => {
    return { setSequence };
};