export const useActiveOption = () => {
    const activeOptions = [
        {
            key: "active",
            label: "Active",
        },
        {
            key: "draft",
            label: "Draft",
        },
    ];
    return { activeOptions };
};
