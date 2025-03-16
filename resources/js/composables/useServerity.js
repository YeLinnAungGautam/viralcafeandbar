export const useServerity = () => {
    const getSeverity = (status) => {
        switch (status) {
            case "draft":
                return "danger";

            case "active":
                return "success";
        }
    };
    return { getSeverity };
};
