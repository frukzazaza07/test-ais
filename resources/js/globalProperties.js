import helpers from "@/Helpers/index.helper";

export const setGlobal = () => {
    return {
        $helpers: helpers(),
    };
};
